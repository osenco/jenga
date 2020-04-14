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
    "password" => "",
    "username" => "",
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
Jenga::checkAccountBalance();
```

### Generate Mini Statement
```php
Jenga::generateMiniStatement();
```

### Account Inquiry
```php
Jenga::accountInquiry();
```

### Move Money within Equity Bank
```php
Jenga::moveMoneyWithinEquity();
```

### Move mobile Money
```php
Jenga::moveMoneyToMobile();
```

### Move RTGS Money
```php
Jenga::moveMoneyViaRtgs();
```

### Move SWIFT Money
```php
Jenga::moveMoneyViaSwift();
```

### Move EFT Money
```php
Jenga::moveMoneyViaEft();
```

### Move PesaLink Money to Bank
```php
Jenga::moveMoneyViaPesaLinkToBank();
```

### Move PesaLink to Mobile Money
```php
Jenga::moveMoneyViaPesaLinkToMobile();
```

### PesaLink Inquiry
```php
Jenga::pesaLinkInqury();
```

### Check Credit Score
```php
Jenga::checkCreditScore();
```

### Get Forex Rates
```php
Jenga::getForexRates();
```