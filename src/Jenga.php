<?php

namespace Osen\Finserve;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class Jenga
{
    protected static $configs = [];
    protected static $token = "";

    public static function init(array $config = [], $token = null)
    {
        $defaults = array(
            "endpoint" => "",
            "password" => "",
            "username" => "",
            "key" => ""
        );

        self::$configs = array_merge($defaults, $config);

        if (!is_null($token)) {
            self::$token = $token;
        }
    }

    public static function setToken($token)
    {
        self::$token = $token;
    }

    public static function config(string $key = null)
    {
        return self::$configs[$key];
    }

    //method to generate finserve APi token
    public static function generateToken(callable $callback = null)
    {
        $baseUrl = self::config("endpoint");
        $password = self::config("password");
        $username = self::config("username");
        $key = self::config("key");

        $requestBody = [
            "username" => $username,
            "password" => $password
        ];

        $client = new Client();

        try {
            $response = $client->post($baseUrl . "identity/v2/token", [
                "headers" => [
                    "Authorization" => "Basic {$key}",
                    "Content-Type" => "application/x-www-form-urlencoded",
                ],
                "form_params" => $requestBody

            ]);

            $token = json_decode((string) $response->getBody(), true);

            self::setToken($token["access_token"]);

            if (is_array($token)) {
                return;
            }

            if (is_null($callback)) {
                return $token;
            } else {
                return call_user_func_array($callback, $token);
            }
        } catch (BadResponseException $exception) {
            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }
    }

    //post to end point for requests
    public static function remotePost($endurl, $requestBody, $signature)
    {
        $client = new Client();
        $baseUrl = self::config("endpoint");
        $token = self::$token;

        try {
            $response = $client->post($baseUrl . $endurl, [
                "headers" => [
                    "Authorization" => "Bearer {$token}",
                    "Content-Type" => "application/json",
                    "signature" =>  base64_encode($signature)
                ],
                "json" => $requestBody
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (BadResponseException $exception) {
            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }
    }

    public static function remoteGet($endpoint, $signature)
    {
        $client = new Client();
        $baseUrl = self::config("endpoint");
        $token = self::$token;
        try {
            $response = $client->get($baseUrl . $endpoint, [
                "headers" => [
                    "Authorization" => "Bearer {$token}",
                    "Content-Type" => "application/json",
                    "signature" => base64_encode($signature)
                ]
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (BadResponseException $exception) {
            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }
    }

    //specific method for the Send Money Inquiry
    public static function postInquiry($endurl, $requestBody)
    {
        $client = new Client();
        $baseUrl = self::config("endpoint");
        $token = self::$token;

        try {
            $response = $client->post($baseUrl . $endurl, [
                "headers" => [
                    "Authorization" => "Bearer {$token}",
                    "Content-Type" => "application/json",
                ],
                "json" => $requestBody
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (BadResponseException $exception) {
            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }
    }

    public static function getInput(string $key = null, $data = null)
    {
        if (is_null($key)) {
            return is_null($data) ? $_REQUEST : $data;
        } else {
            return is_null($data) ? $_REQUEST[$key] : $data[$key];
        }
    }

    //method to check account balance
    public static function checkAccountBalance()
    {
        $countryCode = "KE";
        $accountId = self::getInput("accountId");
        $endurl = "account/v2/accounts/balances/" . $countryCode . "/" . $accountId;

        $signature = self::signAccountBalance($countryCode, $accountId);

        $response = self::remoteGet($endurl, $signature);

        return $response;
    }

    //method to check the mini statement
    public static function generateMiniStatement()
    {
        $countryCode = "KE";
        $accountId = self::getInput("accountId");
        $endurl = "account/v2/accounts/ministatement/" . $countryCode . "/" . $accountId;
        //sign the request
        $signature = self::signAccountBalance($countryCode, $accountId);
        //send the request to finserve
        $response = self::remoteGet($endurl, $signature);

        return $response;
    }

    //inqury on account
    public static function accountInquiry()
    {
        $countryCode = "KE";
        $accountId = self::getInput("accountId");

        $endurl = "account/v2/search/" . $countryCode . "/" . $accountId;
        //sign the request
        $signature = self::signAccountBalance($countryCode, $accountId);
        //send the request to finserve
        $response = self::remoteGet($endurl, $signature);

        return $response;
    }

    // SIGNATURES - @todo Move to another class

    //generate signature fo internaltransfers
    public static function signInternalTransfer($sourceAccountNumber, $amount, $currencyCode, $reference)
    {
        $plaintext = $sourceAccountNumber . $amount . $currencyCode . $reference;

        $fp = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for the Mobile Wallets transfers i.e Airtel and Safaricom
    public static function signMobileWalletTransfer($transferAmount, $transferCurrencyCode, $transferReference, $sourceAccountNumber)
    {
        $plaintext = $transferAmount . $transferCurrencyCode . $transferReference . $sourceAccountNumber;

        $fp = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for rtgs transfer of funds
    public static function signRtgsMoneyTransfer($transferReference, $transferDate, $sourceAccountNumber, $destinationAccountNumber, $transferAmount)
    {
        $plaintext = $transferReference . $transferDate . $sourceAccountNumber . $destinationAccountNumber . $transferAmount;

        $fp = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for swift transfer of funds
    public static function signSwiftMoneyTransfer($transferReference, $transferDate, $sourceAccountNumber, $destinationAccountNumber, $transferAmount)
    {
        $plaintext = $transferReference . $transferDate . $sourceAccountNumber . $destinationAccountNumber . $transferAmount;

        $fp = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for eft money transfer
    public static function signEftMoneyTransfer($transferReference, $sourceAccountNumber, $destinationAccountNumber, $transferAmount, $destinationBankCode)
    {
        $plaintext = $transferReference . $sourceAccountNumber . $destinationAccountNumber . $transferAmount . $destinationBankCode;

        $fp = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for pesalink to bank account
    public static function signPesalinkToBankMoneyTransfer($transferAmount, $transferCurrencyCode, $transferReference, $destinationName, $sourceAccountNumber)
    {
        $plaintext = $transferAmount . $transferCurrencyCode . $transferReference . $destinationName . $sourceAccountNumber;

        $fp = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for pesalink to mobile account
    public static function signPesalinkToMobileMoneyTransfer($transferAmount, $transferCurrencyCode, $transferReference, $destinationName, $sourceAccountNumber)
    {
        $plaintext = $transferAmount . $transferCurrencyCode . $transferReference . $destinationName . $sourceAccountNumber;

        $fp = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature fro credit score
    public static function signCreditScore($dateOfBirth, $merchantCode, $documentNumber)
    {
        $plaintext = $dateOfBirth . $merchantCode . $documentNumber;

        $fp = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
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

        $signature = self::signInternalTransfer($sourceAccountNo, $transferAmount, $transferCurrencyCode, $transferReference);

        $response  = self::remotePost($endurl, $data, $signature);

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
        $signature = self::signMobileWalletTransfer($transferAmount, $transferCurrencyCode, $transferReference, $sourceAccount);

        $response = self::remotePost($endurl, $data, $signature);

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

        $signature = self::signRtgsMoneyTransfer($transferReference, $transferDate, $sourceAccount, $destinationAccount, $transferAmount);

        $response = self::remotePost($endurl, $data, $signature);

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

        $signature = self::signSwiftMoneyTransfer($transferReference, $transferDate, $sourceAccount, $destinationAccount, $transferAmount);

        $response = self::remotePost($endurl, $data, $signature);

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

        $signature = self::signEftMoneyTransfer($transferReference, $sourceAccount, $destinationAccount, $transferAmount, $destinationBankCode);

        $response = self::remotePost($endurl, $data, $signature);

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

        $signature = self::signPesalinkToBankMoneyTransfer($transferAmount, $transferCurrencyCode, $transferReference, $destinationName, $sourceAccount);

        $response = self::remotePost($endurl, $data, $signature);

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

        $signature = self::signPesalinkToMobileMoneyTransfer($transferAmount, $transferCurrencyCode, $transferReference, $destinationName, $sourceAccount);

        $response = self::remotePost($endurl, $data, $signature);

        return $response;
    }

    //method to query pesalink account
    public static function pesaLinkInqury()
    {
        $endurl =  "transaction/v2/pesalink/inquire";
        $mobileNumber = self::getInput("mobileNumber");

        $data = array();
        $data["mobileNumber"] = $mobileNumber;

        $response = self::postInquiry($endurl, $data);

        return $response;
    }

    //method to do credit scoring
    public static function checkCreditScore()
    {
        // to-do Set data if null
        $data = $_REQUEST;
        $endurl = "customer/v2/creditinfo";

        $dateOfBirth = $data["customer"][0]["dateOfBirth"];
        $merchantCode = self::config("merchantCode");
        $documentNumber = $data["customer"][0]["identityDocument"]["documentNumber"];

        $signature = self::signCreditScore($dateOfBirth, $merchantCode, $documentNumber);

        $response = self::remotePost($endurl, $data, $signature);

        return $response;
    }


    //get forex rates
    public static function getForexRates()
    {
        $countryCode = self::getInput("countryCode");
        $currencyCode = self::getInput("currencyCode");

        $endurl = "transaction/v2/foreignexchangerates";

        $data = array();
        $data["countryCode"] = $countryCode;
        $data["currencyCode"] = $currencyCode;

        $response = self::postInquiry($endurl, $data);

        return $response;
    }

    public static function signAccountBalance($countryCode, $accountNo)
    {
        $plaintext = $countryCode . $accountNo;

        $fp = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }
}
