**Token Preise Plugin**  
===================

Dieses Plugin zeigt den aktuellen Preis verschiedener Kryptowährungen an, die auf CoinMarketCap verfügbar sind. Die Daten werden über APIs abgerufen und können über Shortcodes in WordPress verwendet werden.

**Installation**  
------------  
1. Laden Sie die Plugin-Dateien in den Ordner `/wp-content/plugins/` Ihrer WordPress-Installation hoch.  
2. Aktivieren Sie das Plugin im WordPress-Adminbereich unter **Plugins > Installierte Plugins**.

**Verwendung**  
----------  
### Preis eines beliebigen Tokens von CoinMarketCap anzeigen  
Verwenden Sie den folgenden Shortcode, um den aktuellen Preis eines beliebigen Tokens von CoinMarketCap anzuzeigen. Ersetzen Sie `SYMBOL` durch das Symbol des gewünschten Tokens (z. B. `BTC` für Bitcoin, `ETH` für Ethereum, `FONE` für FONE Token):

```
[token_price symbol="SYMBOL"]
```

Beispiele:  
- **Bitcoin Preis anzeigen**: `[token_price symbol="BTC"]`  
- **Ethereum Preis anzeigen**: `[token_price symbol="ETH"]`  
- **FONE Token Preis anzeigen**: `[token_price symbol="FONE"]`

**Einstellungen**  
-------------  
Um das Plugin nutzen zu können, muss ein CoinMarketCap API-Schlüssel gesetzt werden. Gehen Sie dazu im WordPress-Adminbereich auf **Token Preise > Einstellungen** und geben Sie dort Ihren API-Schlüssel ein.

