<?php
/**
 * Plugin Name: Token Preise mit Admin API-Schlüssel-Verwaltung
 * Plugin URI: https://torbenb.info/download/
 * Description: Zeigt den aktuellen Preis verschiedener Tokens auf CoinMarketCap an. Der API-Schlüssel kann im Admin-Bereich verwaltet werden.
 * Version: 1.2
 * Author: TorbenB
 * Author URI: https://torbenb.info/
 */

// Menü im Admin-Backend hinzufügen
function token_prices_add_admin_menu() {
    add_menu_page(
        'Token Preise Einstellungen',
        'Token Preise',
        'manage_options',
        'token-preise-settings',
        'token_prices_settings_page',
        'dashicons-admin-generic',
        80
    );
}
add_action('admin_menu', 'token_prices_add_admin_menu');

// Einstellungen registrieren
function token_prices_register_settings() {
    register_setting('token_prices_settings_group', 'token_prices_coinmarketcap_api_key');
}
add_action('admin_init', 'token_prices_register_settings');

// Einstellungsseite rendern
function token_prices_settings_page() {
    ?>
    <div class="wrap">
        <h1>Token Preise Einstellungen</h1>
        <form method="post" action="options.php">
            <?php settings_fields('token_prices_settings_group'); ?>
            <?php do_settings_sections('token_prices_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">CoinMarketCap API-Schlüssel</th>
                    <td><input type="text" name="token_prices_coinmarketcap_api_key" value="<?php echo esc_attr(get_option('token_prices_coinmarketcap_api_key')); ?>" size="50" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Shortcode für Token Preise definieren
function token_price_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'symbol' => 'FONE',
        ),
        $atts
    );

    $api_key = get_option('token_prices_coinmarketcap_api_key');

    if (!$api_key) {
        return '<p>CoinMarketCap API-Schlüssel nicht gesetzt. Bitte setzen Sie den API-Schlüssel in den Einstellungen.</p>';
    }

    // CoinMarketCap-API-URL für das angegebene Token Symbol
    $api_url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=' . strtoupper($atts['symbol']) . '&convert=USD';

    $response = wp_remote_get($api_url, array(
        'headers' => array(
            'X-CMC_PRO_API_KEY' => $api_key
        )
    ));
    if (is_wp_error($response)) {
        return '<p>Fehler beim Abrufen des ' . esc_html($atts['symbol']) . ' Token Preises.</p>';
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (isset($data['data'][strtoupper($atts['symbol'])]['quote']['USD']['price'])) {
        $price = $data['data'][strtoupper($atts['symbol'])]['quote']['USD']['price'];
        return '<p>1 ' . esc_html($atts['symbol']) . ' sind: $' . number_format($price, 9) . ' USD</p>';
    } else {
        return '<p>Preisdaten für ' . esc_html($atts['symbol']) . ' sind momentan nicht verfügbar.</p>';
    }
}
add_shortcode('token_price', 'token_price_shortcode');
