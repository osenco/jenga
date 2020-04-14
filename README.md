# Jenga API PHP Integration

## Installation
Install via composer
```cmd
composer require osenco/jenga
```

## Usage
### Configure the class
```php
use Osen\Finserve\Jenga;

Jenga::init([
    "username" => "",
    "password" => "",
    "key" => ""
]);
```

### Generate Token
```php
Jenga::generateToken(function ($token) {
    # do something with token, like save in db
    return $token;
});
```

### Check Account Balance
```php
Jenga::checkAccountBalance($accountId);
```

### Generate Mini Statement
```php
Jenga::generateMiniStatement($accountId);
```

### Account Inquiry
```php
Jenga::accountInquiry($accountId);
```

### Move Money within Equity Bank
```php
Jenga::moveMoneyWithinEquity($data);
```

### Move mobile Money
```php
Jenga::moveMoneyToMobile($data);
```

### Move RTGS Money
```php
Jenga::moveMoneyViaRTGS($data);
```

### Move SWIFT Money
```php
Jenga::moveMoneyViaSWIFT($data);
```

### Move EFT Money
```php
Jenga::moveMoneyViaEFT($data);
```

### Move PesaLink Money to Bank
```php
Jenga::moveMoneyViaPesaLinkToBank($data);
```

### Move PesaLink to Mobile Money
```php
Jenga::moveMoneyViaPesaLinkToMobile($data);
```

### PesaLink Inquiry
```php
Jenga::pesaLinkInqury($phone);
```

### Check Credit Score
```php
Jenga::checkCreditScore($data);
```

### Get Forex Rates
```php
Jenga::getForexRates($countryCode, $currencyCode);
```