# Verotel FlexPay library

This library allows merchants to use Verotel payment gateway and get paid
by their users via Credit card and other payment methods.

[![Tests Status](https://travis-ci.org/verotel/flexpay-php-client.svg?branch=master)](https://travis-ci.org/verotel/flexpay-php-client)

## Official Documentation

Documentation PDF for the library can be found on the [Verotel website](http://www.verotel.com/en/integration.html).

## Installation

You can use **Composer** or simply **Download the Release**

### Composer

```
composer require verotel/flexpay-php-client
```

### Download the latest release

[download latest release](https://github.com/verotel/flexpay-php-client/releases/tag/latest-release)

## Usage

### Include

**Composer**
```php
require_once 'vendor/autoload.php';
```

**direct require**
```php
require_once '<path-to-flexpay-php-client>/src/Verotel/FlexPay/Client.php';
```

### Construction of client

```php
// get your brand instance (Verotel, CardBilling, FreenomPay)
$brand = Verotel\FlexPay\Brand::create_from_merchant_id(/* Your customer ID */ '9804000000000000');

$flexpayClient = new Verotel\FlexPay\Client(/* shop ID */ 12345, "FlexPay Signature Key", $brand);
```

### Obtaining of purchase payment url

```php
$purchaseUrl = $flexpayClient->get_purchase_URL([
    "priceAmount" => 2.64,
    "priceCurrency" => "EUR",
    "description" => "Test purchase",
]);
```

### Validation of postback parameters

```php
if (!$flexpayClient->validate_signature($_GET)){
    http_response_code(500);
    echo "ERROR - Invalid signature!";
    exit;
}

// handle correct postback
...

echo "OK";
```

## License

The Verotel Flexpay PHP library is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
