<?php

namespace Osen\Finserve;

use Osen\Finserve\Equity;

class Jenga extends Equity
{
    //method to check account balance
    public static function checkAccountBalance()
    {
        $countryCode = "KE";
        $accountId = parent::getInput("accountId");
        $endurl = "account/v2/accounts/balances/" . $countryCode . "/" . $accountId;

        $signature = parent::signAccountBalance($countryCode, $accountId);

        $response = parent::remoteGet($endurl, $signature);

        return $response;
    }

    //method to check the mini statement
    public static function generateMiniStatement()
    {
        $countryCode = "KE";
        $accountId = parent::getInput("accountId");
        $endurl = "account/v2/accounts/ministatement/" . $countryCode . "/" . $accountId;
        //sign the request
        $signature = parent::signAccountBalance($countryCode, $accountId);
        //send the request to finserve
        $response = parent::remoteGet($endurl, $signature);

        return $response;
    }

    //inqury on account
    public static function accountInquiry()
    {
        $countryCode = "KE";
        $accountId = parent::getInput("accountId");

        $endurl = "account/v2/search/" . $countryCode . "/" . $accountId;
        //sign the request
        $signature = parent::signAccountBalance($countryCode, $accountId);
        //send the request to finserve
        $response = parent::remoteGet($endurl, $signature);

        return $response;
    }


    // Functional Methods

    //move money within equity account
    public static function moveMoneyWithinEquity()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";

        $sourceAccountNo = $data["source"]["accountNumber"];
        $transferAmount = $data["transfer"]["amount"];
        $transferCurrencyCode = $data["transfer"]["currencyCode"];
        $transferReference = $data["transfer"]["reference"];

        $signature = parent::signInternalTransfer($sourceAccountNo, $transferAmount, $transferCurrencyCode, $transferReference);

        $response  = parent::remotePost($endurl, $data, $signature);

        return $response;
    }

    //move money to mobile wallet
    public static function moveMoneyToMobile()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";
        $transferAmount = $data["transfer"]["amount"];
        $transferCurrencyCode = $data["transfer"]["currencyCode"];
        $transferReference = $data["transfer"]["reference"];
        $sourceAccount = $data["source"]["accountNumber"];

        //generate the signature
        $signature = parent::signMobileWalletTransfer($transferAmount, $transferCurrencyCode, $transferReference, $sourceAccount);

        $response = parent::remotePost($endurl, $data, $signature);

        return $response;
    }

    //move money via RTGS
    public static function moveMoneyViaRtgs()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";
        $transferReference = $data["transfer"]["reference"];
        $transferDate = $data["transfer"]["date"];
        $sourceAccount = $data["source"]["accountNumber"];
        $destinationAccount = $data["destination"]["accountNumber"];
        $transferAmount = $data["transfer"]["amount"];

        $signature = parent::signRtgsMoneyTransfer($transferReference, $transferDate, $sourceAccount, $destinationAccount, $transferAmount);

        $response = parent::remotePost($endurl, $data, $signature);

        return $response;
    }

    //move money via SWIFT
    public static function moveMoneyViaSwift()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";
        $transferReference = $data["transfer"]["reference"];
        $transferDate = $data["transfer"]["date"];
        $sourceAccount = $data["source"]["accountNumber"];
        $destinationAccount = $data["destination"]["accountNumber"];
        $transferAmount = $data["transfer"]["amount"];

        $signature = parent::signSwiftMoneyTransfer($transferReference, $transferDate, $sourceAccount, $destinationAccount, $transferAmount);

        $response = parent::remotePost($endurl, $data, $signature);

        return $response;
    }

    //move money via EFT
    public static function moveMoneyViaEft()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";

        $transferReference = $data["transfer"]["reference"];
        $sourceAccount = $data["source"]["accountNumber"];
        $destinationAccount = $data["destination"]["accountNumber"];
        $transferAmount = $data["transfer"]["amount"];
        $destinationBankCode = $data["destination"]["bankCode"];

        $signature = parent::signEftMoneyTransfer($transferReference, $sourceAccount, $destinationAccount, $transferAmount, $destinationBankCode);

        $response = parent::remotePost($endurl, $data, $signature);

        return $response;
    }

    //method to move money via pesalink to a bank account
    public static function moveMoneyViaPesaLinkToBank()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";

        $transferAmount = $data["transfer"]["amount"];
        $transferCurrencyCode = $data["transfer"]["currencyCode"];
        $transferReference = $data["transfer"]["reference"];
        $destinationName = $data["destination"]["name"];
        $sourceAccount = $data["source"]["accountNumber"];

        $signature = parent::signPesalinkToBankMoneyTransfer($transferAmount, $transferCurrencyCode, $transferReference, $destinationName, $sourceAccount);

        $response = parent::remotePost($endurl, $data, $signature);

        return $response;
    }

    //method to move money via pesalink to a mobile account
    public static function moveMoneyViaPesaLinkToMobile()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl =  "transaction/v2/remittance";

        $transferAmount = $data["transfer"]["amount"];
        $transferCurrencyCode = $data["transfer"]["currencyCode"];
        $transferReference = $data["transfer"]["reference"];
        $destinationName = $data["destination"]["name"];
        $sourceAccount = $data["source"]["accountNumber"];

        $signature = parent::signPesalinkToMobileMoneyTransfer($transferAmount, $transferCurrencyCode, $transferReference, $destinationName, $sourceAccount);

        $response = parent::remotePost($endurl, $data, $signature);

        return $response;
    }

    //method to query pesalink account
    public static function pesaLinkInqury()
    {
        $endurl =  "transaction/v2/pesalink/inquire";
        $mobileNumber = parent::getInput("mobileNumber");

        $data = array();
        $data["mobileNumber"] = $mobileNumber;

        $response = parent::postInquiry($endurl, $data);

        return $response;
    }

    //method to do credit scoring
    public static function checkCreditScore()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl = "customer/v2/creditinfo";

        $dateOfBirth = $data["customer"][0]["dateOfBirth"];
        $merchantCode = parent::config("merchantCode");
        $documentNumber = $data["customer"][0]["identityDocument"]["documentNumber"];

        $signature = parent::signCreditScore($dateOfBirth, $merchantCode, $documentNumber);

        $response = parent::remotePost($endurl, $data, $signature);

        return $response;
    }


    //get forex rates
    public static function getForexRates()
    {
        $countryCode = parent::getInput("countryCode");
        $currencyCode = parent::getInput("currencyCode");

        $endurl = "transaction/v2/foreignexchangerates";

        $data = array();
        $data["countryCode"] = $countryCode;
        $data["currencyCode"] = $currencyCode;

        $response = parent::postInquiry($endurl, $data);

        return $response;
    }

    public static function signAccountBalance($countryCode, $accountNo)
    {
        $plaintext = $countryCode . $accountNo;

        $fp = fopen(parent::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }
}
