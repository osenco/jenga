<?php

namespace App\Http\Controllers;

use Osen\Finserve\Jenga;

class JengaController extends Controller
{
    public function generate_token()
    {
        return Jenga::generateToken(function ($token) {
            # do something with token, like save in db
            return $token;
        });
    }

    public function account_balance($accountId = null)
    {
        return Jenga::checkAccountBalance($accountId);
    }

    public function account_mini_statement($accountId = null)
    {
        return Jenga::generateMiniStatement($accountId);
    }

    public function account_inquiry($accountId = null)
    {
        return Jenga::accountInquiry($accountId);
    }

    public function move_money_within($data = [])
    {
        return Jenga::moveMoneyWithinEquity($data);
    }

    public function move_mobile_money($data = [])
    {
        return Jenga::moveMoneyToMobile($data);
    }

    public function move_rtgs_money($data = [])
    {
        return Jenga::moveMoneyViaRTGS($data);
    }

    public function move_swift_money($data = [])
    {
        return Jenga::moveMoneyViaSWIFT($data);
    }
    public function move_eft_money($data = [])
    {
        return Jenga::moveMoneyViaEFT($data);
    }

    public function move_pesalink_money_bank($data = [])
    {
        return Jenga::moveMoneyViaPesaLinkToBank($data);
    }

    public function move_pesalink_money_mobile($data = [])
    {
        return Jenga::moveMoneyViaPesaLinkToMobile($data);
    }

    public function inquire_pesalink($phone)
    {
        return Jenga::pesaLinkInqury($phone);
    }

    public function check_credit_score($data = [])
    {
        return Jenga::checkCreditScore($data);
    }

    public function get_forex_rates($countryCode = 'KE', $currencyCode = 'KES')
    {
        return Jenga::getForexRates($countryCode, $currencyCode);
    }
}
