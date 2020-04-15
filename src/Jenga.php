<?php

namespace Osen\Finserve;

use Osen\Finserve\Equity;

class Jenga extends Equity
{
    //method to check account balance
    public static function checkAccountBalance($accountId = null, $countryCode = "KE")
    {
        $endpoint = "account/v2/accounts/balances/" . $countryCode . "/" . $accountId;

        $signature = parent::signAccountBalance($countryCode, $accountId);

        $response = parent::remoteGet($endpoint, $signature);

        return $response;
    }

    //method to check the mini statement
    public static function generateMiniStatement($accountId = null, $countryCode = "KE")
    {
        $endpoint = "account/v2/accounts/ministatement/" . $countryCode . "/" . $accountId;
        //sign the request
        $signature = parent::signAccountBalance($countryCode, $accountId);
        //send the request to finserve
        $response = parent::remoteGet($endpoint, $signature);

        return $response;
    }

    //inqury on account
    public static function accountInquiry($accountId = null, $countryCode = "KE")
    {
        $endpoint = "account/v2/search/" . $countryCode . "/" . $accountId;
        //sign the request
        $signature = parent::signAccountBalance($countryCode, $accountId);
        //send the request to finserve
        $response = parent::remoteGet($endpoint, $signature);

        return $response;
    }

    // Functional Methods

    //move money within equity account
    public static function moveMoneyWithinEquity($data = [])
    {
        $endpoint = "transaction/v2/remittance";

        $sourceAccountNo      = $data["source"]["accountNumber"];
        $transferAmount       = $data["transfer"]["amount"];
        $transferCurrencyCode = $data["transfer"]["currencyCode"];
        $transferReference    = $data["transfer"]["reference"];

        $signature = parent::signTransaction($sourceAccountNo, $transferAmount, $transferCurrencyCode, $transferReference);

        $response = parent::remotePost($endpoint, $data, $signature);

        return $response;
    }

    //move money to mobile wallet
    public static function moveMoneyToMobile($data = [])
    {
        $endpoint             = "transaction/v2/remittance";
        $transferAmount       = $data["transfer"]["amount"];
        $transferCurrencyCode = $data["transfer"]["currencyCode"];
        $transferReference    = $data["transfer"]["reference"];
        $sourceAccount        = $data["source"]["accountNumber"];

        //generate the signature
        $signature = parent::signTransaction($transferAmount, $transferCurrencyCode, $transferReference, $sourceAccount);

        $response = parent::remotePost($endpoint, $data, $signature);

        return $response;
    }

    //move money via RTGS
    public static function moveMoneyViaRTGS($data = [])
    {
        $endpoint           = "transaction/v2/remittance";
        $transferReference  = $data["transfer"]["reference"];
        $transferDate       = $data["transfer"]["date"];
        $sourceAccount      = $data["source"]["accountNumber"];
        $destinationAccount = $data["destination"]["accountNumber"];
        $transferAmount     = $data["transfer"]["amount"];

        $signature = parent::signTransaction($transferReference, $transferDate, $sourceAccount, $destinationAccount, $transferAmount);

        $response = parent::remotePost($endpoint, $data, $signature);

        return $response;
    }

    //move money via SWIFT
    public static function moveMoneyViaSWIFT($data = [])
    {
        $endpoint           = "transaction/v2/remittance";
        $transferReference  = $data["transfer"]["reference"];
        $transferDate       = $data["transfer"]["date"];
        $sourceAccount      = $data["source"]["accountNumber"];
        $destinationAccount = $data["destination"]["accountNumber"];
        $transferAmount     = $data["transfer"]["amount"];

        $signature = parent::signTransaction($transferReference, $transferDate, $sourceAccount, $destinationAccount, $transferAmount);

        $response = parent::remotePost($endpoint, $data, $signature);

        return $response;
    }

    //move money via EFT
    public static function moveMoneyViaEFT($data = [])
    {
        $endpoint = "transaction/v2/remittance";

        $transferReference   = $data["transfer"]["reference"];
        $sourceAccount       = $data["source"]["accountNumber"];
        $destinationAccount  = $data["destination"]["accountNumber"];
        $transferAmount      = $data["transfer"]["amount"];
        $destinationBankCode = $data["destination"]["bankCode"];

        $signature = parent::signTransaction($transferReference, $sourceAccount, $destinationAccount, $transferAmount, $destinationBankCode);

        $response = parent::remotePost($endpoint, $data, $signature);

        return $response;
    }

    //method to move money via pesalink to a bank account
    public static function moveMoneyViaPesaLinkToBank($data = [])
    {
        $endpoint = "transaction/v2/remittance";

        $transferAmount       = $data["transfer"]["amount"];
        $transferCurrencyCode = $data["transfer"]["currencyCode"];
        $transferReference    = $data["transfer"]["reference"];
        $destinationName      = $data["destination"]["name"];
        $sourceAccount        = $data["source"]["accountNumber"];

        $signature = parent::signTransaction($transferAmount, $transferCurrencyCode, $transferReference, $destinationName, $sourceAccount);

        $response = parent::remotePost($endpoint, $data, $signature);

        return $response;
    }

    //method to move money via pesalink to a mobile account
    public static function moveMoneyViaPesaLinkToMobile($data = [])
    {
        $endpoint = "transaction/v2/remittance";

        $transferAmount       = $data["transfer"]["amount"];
        $transferCurrencyCode = $data["transfer"]["currencyCode"];
        $transferReference    = $data["transfer"]["reference"];
        $destinationName      = $data["destination"]["name"];
        $sourceAccount        = $data["source"]["accountNumber"];

        $signature = parent::signTransaction($transferAmount, $transferCurrencyCode, $transferReference, $destinationName, $sourceAccount);

        $response = parent::remotePost($endpoint, $data, $signature);

        return $response;
    }

    //method to query pesalink account
    public static function pesaLinkInqury($phone = null)
    {
        $endpoint = "transaction/v2/pesalink/inquire";

        $data = array(
            "mobileNumber" => $phone,
        );

        $response = parent::postInquiry($endpoint, $data);

        return $response;
    }

    //method to do credit scoring
    public static function checkCreditScore($data = [])
    {
        $endpoint = "customer/v2/creditinfo";

        $dateOfBirth    = $data["customer"][0]["dateOfBirth"];
        $merchantCode   = parent::config("merchantCode");
        $documentNumber = $data["customer"][0]["identityDocument"]["documentNumber"];

        $signature = parent::signTransaction($dateOfBirth, $merchantCode, $documentNumber);

        $response = parent::remotePost($endpoint, $data, $signature);

        return $response;
    }

    //get forex rates
    public static function getForexRates($countryCode = 'KE', $currencyCode = 'KES')
    {
        $endpoint = "transaction/v2/foreignexchangerates";

        $data = array(
            "countryCode"  => $countryCode,
            "currencyCode" => $currencyCode,
        );

        $response = parent::postInquiry($endpoint, $data);

        return $response;
    }
}
