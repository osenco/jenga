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

### move money within Equity
```php
Jenga::moveMoneyWithinEquity();
```

### move mobile money
```php
Jenga::moveMoneyToMobile();
```

### move rtgs money
```php
Jenga::moveMoneyViaRtgs();
```

### move swift money
```php
Jenga::moveMoneyViaSwift();
```

### move eft money
```php
Jenga::moveMoneyViaEft();
```

### move pesalink money bank
```php
Jenga::moveMoneyViaPesaLinkToBank();
```

### move pesalink money mobile
```php
Jenga::moveMoneyViaPesaLinkToMobile();
```

### money inquire
```php
Jenga::pesaLinkInqury();
```

### check credit score
```php
Jenga::checkCreditScore();
```

### get forex rates
```php
Jenga::getForexRates();
```