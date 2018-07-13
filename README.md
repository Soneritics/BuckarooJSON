[![Build Status](https://api.travis-ci.org/Soneritics/BuckarooJSON.svg?branch=master)](https://travis-ci.org/Soneritics/BuckarooJSON)
[![Coverage Status](https://coveralls.io/repos/github/Soneritics/BuckarooJSON/badge.svg?branch=master)](https://coveralls.io/github/Soneritics/BuckarooJSON?branch=master)
[![Latest Stable](https://img.shields.io/packagist/v/soneritics/buckaroojson.svg?style=flat-square&label=stable)](https://packagist.org/packages/soneritics/buckaroojson?branch=master)
![License](http://img.shields.io/badge/license-MIT-green.svg)

# Buckaroo
> Buckaroo payment provider implementation classes

Connect with Buckaroo through their easy to use JSON API.

## Payment methods
Currently, the following payment methods are supported
* iDeal
* AfterPay (DigiAccept)
* Bancontact
* Credit cards:
    * Mastercard
    * Visa
    * American Express
* Debit cards:
    * VPay
    * Maestro
    * Visa Electron
    * Carte Bleue
    * Carte Bancaire
    * Dankort

## Example
Some example code has been added in the _Examples_ folder. A sneak peek below :-)

```php
$transactionKey = ''; // Your transaction key here

$authentication = new Authentication($secretKey, $websiteKey);
$buckaroo = new Buckaroo($authentication, true);
$transactionStatusRequest = $buckaroo->getTransactionStatusRequest($transactionKey)->request();

if ($transactionStatusRequest['Status']['Code']['Code'] == PaymentStatus::SUCCESS) {
    // Order is paid
}
```
 