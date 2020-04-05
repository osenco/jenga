<?php
namespace App\Http\Controllers;

use Osen\Jenga\Helper;
use Osen\Jenga\Service;
use Osen\Jenga\Signature;

class JengaController extends Controller
{
    public function generate_token()
    {
        return Helper::generateToken(function ($token)
        {
            # do something with token, like save in db
            return $token;
        });
    }

    public function account_balance()
    {
        return Helper::checkAccountBalance();
    }

    public function account_mini_statement()
    {
        return Helper::generateMiniStatement();
    }

    public function account_inquiry()
    {
        return Helper::accountInquiry();
    }

    public function move_money_within()
    {
        return Helper::moveMoneyWithinEquity();
    }
    
    public function move_mobile_money()
    {
        return Helper::moveMoneyToMobile();
    }
    
    public function move_rtgs_money()
    {
        return Helper::moveMoneyViaRtgs();
    }
    public function move_swift_money()
    {
        return Helper::moveMoneyViaSwift();
    }
    public function move_eft_money()
    {
        return Helper::moveMoneyViaEft();
    }
    
    public function move_pesalink_money_bank()
    {
        return Helper::moveMoneyViaPesaLinkToBank();
    }
    
    public function move_pesalink_money_mobile()
    {
        return Helper::moveMoneyViaPesaLinkToMobile();
    }

    public function money_inquire()
    {
        return Helper::pesaLinkInqury();
    }

    public function check_credit_score()
    {
        return Helper::checkCreditScore();
    }

    public function get_forex_rates()
    {
        return Helper::getForexRates();
    }
}