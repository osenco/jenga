<?php

function setup_jenga($config)
{
    return Osen\Finserve\Jenga::init($config);
}

function jenga_generate_token()
{
    return Osen\Finserve\Jenga::generateToken(function ($token) {
        # do something with token, like save in db
        return $token;
    });
}

function jenga_account_balance($accountId = null)
{
    return Osen\Finserve\Jenga::checkAccountBalance($accountId);
}

function jenga_account_mini_statement($accountId = null)
{
    return Osen\Finserve\Jenga::generateMiniStatement($accountId);
}

function jenga_account_inquiry($accountId = null)
{
    return Osen\Finserve\Jenga::accountInquiry($accountId);
}

function jenga_move_money_within($data = [])
{
    return Osen\Finserve\Jenga::moveMoneyWithinEquity($data);
}

function jenga_move_mobile_money($data = [])
{
    return Osen\Finserve\Jenga::moveMoneyToMobile($data);
}

function jenga_move_rtgs_money($data = [])
{
    return Osen\Finserve\Jenga::moveMoneyViaRTGS($data);
}

function jenga_move_swift_money($data = [])
{
    return Osen\Finserve\Jenga::moveMoneyViaSWIFT($data);
}
function jenga_move_eft_money($data = [])
{
    return Osen\Finserve\Jenga::moveMoneyViaEFT($data);
}

function jenga_move_pesalink_money_bank($data = [])
{
    return Osen\Finserve\Jenga::moveMoneyViaPesaLinkToBank($data);
}

function jenga_move_pesalink_money_mobile($data = [])
{
    return Osen\Finserve\Jenga::moveMoneyViaPesaLinkToMobile($data);
}

function jenga_inquire_pesalink($phone)
{
    return Osen\Finserve\Jenga::pesaLinkInqury($phone);
}

function jenga_check_credit_score($data = [])
{
    return Osen\Finserve\Jenga::checkCreditScore($data);
}

function jenga_get_forex_rates($countryCode = 'KE', $currencyCode = 'KES')
{
    return Osen\Finserve\Jenga::getForexRates($countryCode, $currencyCode);
}
