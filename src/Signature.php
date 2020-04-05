<?php

namespace Osen\Jenga;

use Osen\Jenga\Service;

class Signature
{
    //generate signature fo internaltransfers
    public static function signInternalTransfer($sourceAccountNumber, $amount, $currencyCode, $reference)
    {
        $plaintext =$sourceAccountNumber.$amount.$currencyCode.$reference;

        $fp = fopen(Service::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "jenga");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for the Mobile Wallets transfers i.e Airtel and Safaricom
    public static function signMobileWalletTransfer($transferAmount, $transferCurrencyCode, $transferReference, $sourceAccountNumber)
    {
        $plaintext = $transferAmount.$transferCurrencyCode.$transferReference.$sourceAccountNumber;

        $fp = fopen(Service::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "jenga");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for rtgs transfer of funds
    public static function signRtgsMoneyTransfer($transferReference, $transferDate, $sourceAccountNumber, $destinationAccountNumber, $transferAmount)
    {
        $plaintext = $transferReference.$transferDate.$sourceAccountNumber.$destinationAccountNumber.$transferAmount;

        $fp = fopen(Service::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "jenga");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for swift transfer of funds
    public static function signSwiftMoneyTransfer($transferReference, $transferDate, $sourceAccountNumber, $destinationAccountNumber, $transferAmount)
    {
        $plaintext = $transferReference.$transferDate.$sourceAccountNumber.$destinationAccountNumber.$transferAmount;

        $fp = fopen(Service::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "jenga");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for eft money transfer
    public static function signEftMoneyTransfer($transferReference, $sourceAccountNumber, $destinationAccountNumber, $transferAmount, $destinationBankCode)
    {
        $plaintext = $transferReference.$sourceAccountNumber.$destinationAccountNumber.$transferAmount.$destinationBankCode;

        $fp = fopen(Service::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "jenga");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for pesalink to bank account
    public static function signPesalinkToBankMoneyTransfer($transferAmount, $transferCurrencyCode, $transferReference, $destinationName, $sourceAccountNumber)
    {
        $plaintext = $transferAmount.$transferCurrencyCode.$transferReference.$destinationName.$sourceAccountNumber;

        $fp = fopen(Service::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "jenga");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature for pesalink to mobile account
    public static function signPesalinkToMobileMoneyTransfer($transferAmount, $transferCurrencyCode, $transferReference, $destinationName, $sourceAccountNumber)
    {
        $plaintext = $transferAmount.$transferCurrencyCode.$transferReference.$destinationName.$sourceAccountNumber;

        $fp = fopen(Service::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "jenga");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    //generate signature fro credit score
    public static function signCreditScore($dateOfBirth, $merchantCode, $documentNumber)
    {
        $plaintext = $dateOfBirth.$merchantCode.$documentNumber;

        $fp = fopen(Service::config("private_key"), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key, "jenga");

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }
}