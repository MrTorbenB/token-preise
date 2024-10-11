<?php
/*
Plugin Name: Token Preise mit Admin API-Schlüssel-Verwaltung
Plugin URI: https://torbenb.info/download/
Description: Zeigt den aktuellen Preis des Pi Tokens auf Huobi und des FONE Tokens auf CoinMarketCap an. Der API-Schlüssel kann im Admin-Bereich verwaltet werden.
Version: 1.2
Author: TorbenB
Author URI: https://torbenb.info/
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

// Shortcode für PI Token definieren
function pi_token_price_shortcode() {
    // Huobi-API-URL für das PI/USDt-Paar
    $huobi_api_url = 'https://api.huobi.pro/market/detail/merged?symbol=piusdt';
    
    // Huobi-API-Aufruf
    $response = wp_remote_get($huobi_api_url);
    if (is_wp_error($response)) {
        return '<p>Fehler beim Abrufen des Pi Token Preises.</p>';
    }
    
    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (isset($data['tick']['close'])) {
        $huobi_price = $data['tick']['close'];
        return '<p>1 PI sind: $' . number_format($huobi_price, 2) . ' USD</p>';
    } else {
        return '<p>Preisdaten sind momentan nicht verfügbar.</p>';
    }
}
add_shortcode('pi_token_price', 'pi_token_price_shortcode');

// Shortcode für FONE Token definieren
function fone_token_price_shortcode() {
    $api_key = get_option('token_prices_coinmarketcap_api_key');

    if (!$api_key) {
        return '<p>CoinMarketCap API-Schlüssel nicht gesetzt. Bitte setzen Sie den API-Schlüssel in den Einstellungen.</p>';
    }

    // CoinMarketCap-API-URL für FONE/USD
    $api_url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=FONE&convert=USD';

    $response = wp_remote_get($api_url, array(
        'headers' => array(
            'X-CMC_PRO_API_KEY' => $api_key
        )
    ));
    if (is_wp_error($response)) {
        return '<p>Fehler beim Abrufen des FONE Token Preises.</p>';
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (isset($data['data']['FONE']['quote']['USD']['price'])) {
        $price = $data['data']['FONE']['quote']['USD']['price'];
        return '<p>1 FONE sind: $' . number_format($price, 9) . ' USD</p>';
    } else {
        return '<p>Preisdaten sind momentan nicht verfügbar.</p>';
    }
}
add_shortcode('fone_token_price', 'fone_token_price_shortcode');