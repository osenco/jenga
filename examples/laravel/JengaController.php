<?php

namespace App\Http\Controllers;

use Osen\Finserve\Jenga;
use Osen\Finserve\Equity;
use Osen\Finserve\Signature;

class JengaController extends Controller
{
    public function generate_token()
    {
        return Jenga::generateToken(function ($token) {
            # do something with token, like save in db
            return $token;
        });
    }

    public function account_balance()
    {
        return Jenga::checkAccountBalance();
    }

    public function account_mini_statement()
    {
        return Jenga::generateMiniStatement();
    }

    public function account_inquiry()
    {
        return Jenga::accountInquiry();
    }

    public function move_money_within()
    {
        return Jenga::moveMoneyWithinEquity();
    }

    public function move_mobile_money()
    {
        return Jenga::moveMoneyToMobile();
    }

    public function move_rtgs_money()
    {
        return Jenga::moveMoneyViaRtgs();
    }

    public function move_swift_money()
    {
        return Jenga::moveMoneyViaSwift();
    }
    public function move_eft_money()
    {
        return Jenga::moveMoneyViaEft();
    }

    public function move_pesalink_money_bank()
    {
        return Jenga::moveMoneyViaPesaLinkToBank();
    }

    public function move_pesalink_money_mobile()
    {
        return Jenga::moveMoneyViaPesaLinkToMobile();
    }

    public function money_inquire()
    {
        return Jenga::pesaLinkInqury();
    }

    public function check_credit_score()
    {
        return Jenga::checkCreditScore();
    }

    public function get_forex_rates()
    {
        return Jenga::getForexRates();
    }
}
