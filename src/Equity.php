<?php

namespace Osen\Finserve;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class Equity
{
    protected static $configs = [];
    protected static $token   = "";

    public static function init(array $config = [], $token = null)
    {
        $endpoint = (isset($config['env']) && ($config['env'] == 'live'))
            ? "https://jengahq.io/"
            : "https://sandbox.jengahq.io/";

        $defaults = array(
            "endpoint"    => $endpoint,
            "username"    => "",
            "password"    => "",
            "key"         => "",
            "private_key" => __DIR__ . "/privatekey.pem",
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
        $baseUrl  = self::config("endpoint");
        $password = self::config("password");
        $username = self::config("username");
        $key      = self::config("key");

        $requestBody = [
            "username" => $username,
            "password" => $password,
        ];

        $client = new Client();

        try {
            $response = $client->post($baseUrl . "identity/v2/token", [
                "headers"     => [
                    "Authorization" => "Basic {$key}",
                    "Content-Type"  => "application/x-www-form-urlencoded",
                ],
                "form_params" => $requestBody,

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
    public static function remotePost($endpoint, $requestBody, $signature)
    {
        $client  = new Client();
        $baseUrl = self::config("endpoint");
        $token   = self::$token;

        try {
            $response = $client->post($baseUrl . $endpoint, [
                "headers" => [
                    "Authorization" => "Bearer {$token}",
                    "Content-Type"  => "application/json",
                    "signature"     => base64_encode($signature),
                ],
                "json"    => $requestBody,
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (BadResponseException $exception) {
            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }
    }

    public static function remoteGet($endpoint, $signature)
    {
        $client  = new Client();
        $baseUrl = self::config("endpoint");
        $token   = self::$token;
        try {
            $response = $client->get($baseUrl . $endpoint, [
                "headers" => [
                    "Authorization" => "Bearer {$token}",
                    "Content-Type"  => "application/json",
                    "signature"     => base64_encode($signature),
                ],
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (BadResponseException $exception) {
            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }
    }

    //specific method for the Send Money Inquiry
    public static function postInquiry($endpoint, $requestBody)
    {
        $client  = new Client();
        $baseUrl = self::config("endpoint");
        $token   = self::$token;

        try {
            $response = $client->post($baseUrl . $endpoint, [
                "headers" => [
                    "Authorization" => "Bearer {$token}",
                    "Content-Type"  => "application/json",
                ],
                "json"    => $requestBody,
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

    // SIGNATURES - @todo Move to another class
    //generate signature fo internaltransfers
    public static function signInternalTransfer($sourceAccountNumber, $amount, $currencyCode, $reference)
    {
        $plaintext = $sourceAccountNumber . $amount . $currencyCode . $reference;

        $fp       = fopen(self::config("private_key"), "r");
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

        $fp       = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for RTGS transfer of funds
    public static function signRTGSMoneyTransfer($transferReference, $transferDate, $sourceAccountNumber, $destinationAccountNumber, $transferAmount)
    {
        $plaintext = $transferReference . $transferDate . $sourceAccountNumber . $destinationAccountNumber . $transferAmount;

        $fp       = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for SWIFT transfer of funds
    public static function signSWIFTMoneyTransfer($transferReference, $transferDate, $sourceAccountNumber, $destinationAccountNumber, $transferAmount)
    {
        $plaintext = $transferReference . $transferDate . $sourceAccountNumber . $destinationAccountNumber . $transferAmount;

        $fp       = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for EFT money transfer
    public static function signEFTMoneyTransfer($transferReference, $sourceAccountNumber, $destinationAccountNumber, $transferAmount, $destinationBankCode)
    {
        $plaintext = $transferReference . $sourceAccountNumber . $destinationAccountNumber . $transferAmount . $destinationBankCode;

        $fp       = fopen(self::config("private_key"), "r");
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

        $fp       = fopen(self::config("private_key"), "r");
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

        $fp       = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    public static function signAccountBalance($countryCode, $accountNo)
    {
        $plaintext = $countryCode . $accountNo;

        $fp       = fopen(parent::config("private_key"), "r");
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

        $fp       = fopen(self::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "finserve");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }
}
