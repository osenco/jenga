<?php

namespace Osen\Jenga;

use Osen\Jenga\Service;
use Osen\Jenga\Signature;

class Helper
{
    public static function input(string $key = null)
    {
        return $_REQUEST[$key];
    }

    //method to generate jenga APi token
    public static function generateToken(callable $callback = null)
    {
        $token = Service::generateToken();

        if (is_array($token)) {
            return;
        }

        if (is_null($callback)) {
            return $token;
        } else {
            return call_user_func_array($callback, $token);
        }
    }

    //method to check account balance
    public static function checkAccountBalance()
    {
        $countryCode = "KE";
        $accountId = self::input("accountId");
        $endurl = "account/v2/accounts/balances/".$countryCode."/".$accountId;

        $signature = self::signAccountBalance($countryCode, $accountId);

        $response = Service::get($endurl, $signature);

        return $response;
    }

    //method to check the mini statement
    public static function generateMiniStatement()
    {
        $countryCode = "KE";
        $accountId = self::input("accountId");
        $endurl = "account/v2/accounts/ministatement/".$countryCode."/".$accountId;
        //sign the request
        $signature = self::signAccountBalance($countryCode, $accountId);
        //send the request to jenga
        $response = Service::get($endurl, $signature);

        return $response;
    }

    //inqury on account
    public static function accountInquiry()
    {
        $countryCode = "KE";
        $accountId = self::input("accountId");

        $endurl = "account/v2/search/".$countryCode."/".$accountId;
        //sign the request
        $signature = self::signAccountBalance($countryCode, $accountId);
        //send the request to jenga
        $response = Service::get($endurl, $signature);

        return $response;
    }

    //move money within equity account
    public static function moveMoneyWithinEquity()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";

        $sourceAccountNo= $data["source"]["accountNumber"];
        $transferAmount= $data["transfer"]["amount"];
        $transferCurrencyCode= $data["transfer"]["currencyCode"];
        $transferReference= $data["transfer"]["reference"];

        $signature = Signature::signInternalTransfer($sourceAccountNo, $transferAmount, $transferCurrencyCode, $transferReference);

        $response  = Service::post($endurl, $data, $signature);

        return $response;
    }

    //move money to mobile wallet
    public static function moveMoneyToMobile()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";
        $transferAmount= $data["transfer"]["amount"];
        $transferCurrencyCode = $data["transfer"]["currencyCode"];
        $transferReference= $data["transfer"]["reference"];
        $sourceAccount= $data["source"]["accountNumber"];

        //generate the signature
        $signature = Signature::signMobileWalletTransfer($transferAmount, $transferCurrencyCode, $transferReference, $sourceAccount);

        $response = Service::post($endurl, $data, $signature);

        return $response;
    }

    //move money via RTGS
    public static function moveMoneyViaRtgs()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";
        $transferReference= $data["transfer"]["reference"];
        $transferDate = $data["transfer"]["date"];
        $sourceAccount= $data["source"]["accountNumber"];
        $destinationAccount = $data["destination"]["accountNumber"];
        $transferAmount= $data["transfer"]["amount"];

        $signature = Signature::signRtgsMoneyTransfer($transferReference, $transferDate, $sourceAccount, $destinationAccount, $transferAmount);

        $response = Service::post($endurl, $data, $signature);

        return $response;
    }

    //move money via SWIFT
    public static function moveMoneyViaSwift()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";
        $transferReference= $data["transfer"]["reference"];
        $transferDate = $data["transfer"]["date"];
        $sourceAccount= $data["source"]["accountNumber"];
        $destinationAccount = $data["destination"]["accountNumber"];
        $transferAmount= $data["transfer"]["amount"];

        $signature = Signature::signSwiftMoneyTransfer($transferReference, $transferDate, $sourceAccount, $destinationAccount, $transferAmount);

        $response = Service::post($endurl, $data, $signature);

        return $response;
    }

    //move money via EFT
    public static function moveMoneyViaEft()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";

        $transferReference= $data["transfer"]["reference"];
        $sourceAccount= $data["source"]["accountNumber"];
        $destinationAccount = $data["destination"]["accountNumber"];
        $transferAmount= $data["transfer"]["amount"];
        $destinationBankCode = $data["destination"]["bankCode"];

        $signature = Signature::signEftMoneyTransfer($transferReference, $sourceAccount, $destinationAccount, $transferAmount, $destinationBankCode);

        $response = Service::post($endurl, $data, $signature);

        return $response;
    }

    //method to move money via pesalink to a bank account
    public static function moveMoneyViaPesaLinkToBank()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";

        $transferAmount= $data["transfer"]["amount"];
        $transferCurrencyCode= $data["transfer"]["currencyCode"];
        $transferReference= $data["transfer"]["reference"];
        $destinationName= $data["destination"]["name"];
        $sourceAccount= $data["source"]["accountNumber"];

        $signature = Signature::signPesalinkToBankMoneyTransfer($transferAmount, $transferCurrencyCode, $transferReference, $destinationName, $sourceAccount);

        $response = Service::post($endurl, $data, $signature);
        
        return $response;
    }

    //method to move money via pesalink to a mobile account
    public static function moveMoneyViaPesaLinkToMobile()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";

        $transferAmount= $data["transfer"]["amount"];
        $transferCurrencyCode= $data["transfer"]["currencyCode"];
        $transferReference= $data["transfer"]["reference"];
        $destinationName= $data["destination"]["name"];
        $sourceAccount= $data["source"]["accountNumber"];

        $signature = Signature::signPesalinkToMobileMoneyTransfer($transferAmount, $transferCurrencyCode, $transferReference, $destinationName, $sourceAccount);

        $response = Service::post($endurl, $data, $signature);

        return $response;
    }

    //method to query pesalink account
    public static function pesaLinkInqury()
    {
        $endurl =  "transaction/v2/pesalink/inquire";
        $mobileNumber = self::input("mobileNumber");

        $data = array();
        $data["mobileNumber"] = $mobileNumber;

        $response = Service::postInquiry($endurl, $data);

        return $response;
    }

    //method to do credit scoring
    public static function checkCreditScore()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl = "customer/v2/creditinfo";

        $dateOfBirth = $data["customer"][0]["dateOfBirth"];
        $merchantCode =Service::config("merchantCode");
        $documentNumber = $data["customer"][0]["identityDocument"]["documentNumber"];

        $signature = Signature::signCreditScore($dateOfBirth, $merchantCode, $documentNumber);

        $response = Service::post($endurl, $data, $signature);

        return $response;
    }


    //get forex rates
    public static function getForexRates()
    {
        $countryCode = self::input("countryCode");
        $currencyCode = self::input("currencyCode");

        $endurl = "transaction/v2/foreignexchangerates";

        $data = array();
        $data["countryCode"] = $countryCode;
        $data["currencyCode"] = $currencyCode;

        $response = Service::postInquiry($endurl, $data);

        return $response;
    }

    public static function signAccountBalance($countryCode, $accountNo)
    {
        $plaintext = $countryCode.$accountNo;

        $fp = fopen(Service::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "jenga");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }
}