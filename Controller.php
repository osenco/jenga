<?php

namespace App\Http\Controllers;

use App\Helpers\GenerateSignature;
use App\Helpers\JengaApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use anlutro\LaravelSettings\Facade as Setting;


class HooksController extends Controller
{
    //

    //method to generate jenga APi token
    public function generateToken(){
        $token = JengaApi::generateToken();

        Setting::set('mpesa-api',$token['access_token']);

        Setting::save();
        return $token;
    }

    //method to check account balance
    public function checkAccountBalance(Request $request){
        $countryCode = 'KE';
        $accountId = $request->input('accountId');
        $endurl = 'account/v2/accounts/balances/'.$countryCode.'/'.$accountId;

        $signature = self::signAccountBalance($countryCode,$accountId);

        $response = JengaApi::get($endurl,$signature);

        return $response;

    }

    //method to check the mini statement
    public function generateMiniStatement(Request $request){
        $countryCode = 'KE';
        $accountId = $request->input('accountId');
        $endurl = 'account/v2/accounts/ministatement/'.$countryCode.'/'.$accountId;
        //sign the request
        $signature = self::signAccountBalance($countryCode,$accountId);
        //send the request to jenga
        $response = JengaApi::get($endurl,$signature);

        return $response;

    }

    //inqury on account
    public function accountInquiry(Request $request){
        $countryCode = 'KE';
        $accountId = $request->input('accountId');

        $endurl = 'account/v2/search/'.$countryCode.'/'.$accountId;
        //sign the request
        $signature = self::signAccountBalance($countryCode,$accountId);
        //send the request to jenga
        $response = JengaApi::get($endurl,$signature);

        return $response;
    }

    //move money within equity account
    public function moveMoneyWithinEquity(Request $request){
        $data = $request->toArray();
        $requestBody = $request->all();
        $endurl =  'transaction/v2/remittance';

        $sourceAccountNo= $data['source']['accountNumber'];
        $transferAmount= $data['transfer']['amount'];
        $transferCurrencyCode= $data['transfer']['currencyCode'];
        $transferReference= $data['transfer']['reference'];

        $signature = GenerateSignature::signInternalTransfer($sourceAccountNo,$transferAmount,$transferCurrencyCode,$transferReference);

        $response  = JengaApi::post($endurl,$requestBody,$signature);

        return $response;


    }

    //move money to mobile wallet
    public function moveMoneyToMobile(Request $request){
        $data = $request->toArray();
        $requestBody = $request->all();
        $endurl =  'transaction/v2/remittance';
        $transferAmount= $data['transfer']['amount'];
        $transferCurrencyCode = $data['transfer']['currencyCode'];
        $transferReference= $data['transfer']['reference'];
        $sourceAccount= $data['source']['accountNumber'];

        //generate the signature
        $signature = GenerateSignature::signMobileWalletTransfer($transferAmount,$transferCurrencyCode,$transferReference,$sourceAccount);

        $response = JengaApi::post($endurl,$requestBody,$signature);

        return $response;



    }

    //move money via RTGS
    public function moveMoneyViaRtgs(Request $request){
        $data = $request->toArray();
        $requestBody = $request->all();
        $endurl =  'transaction/v2/remittance';
        $transferReference= $data['transfer']['reference'];
        $transferDate = $data['transfer']['date'];
        $sourceAccount= $data['source']['accountNumber'];
        $destinationAccount = $data['destination']['accountNumber'];
        $transferAmount= $data['transfer']['amount'];

        $signature = GenerateSignature::signRtgsMoneyTransfer($transferReference,$transferDate,$sourceAccount,$destinationAccount,$transferAmount);

        $response = JengaApi::post($endurl,$requestBody,$signature);

        return $response;


    }

    //move money via SWIFT
    public function moveMoneyViaSwift(Request $request){
        $data = $request->toArray();
        $requestBody = $request->all();
        $endurl =  'transaction/v2/remittance';
        $transferReference= $data['transfer']['reference'];
        $transferDate = $data['transfer']['date'];
        $sourceAccount= $data['source']['accountNumber'];
        $destinationAccount = $data['destination']['accountNumber'];
        $transferAmount= $data['transfer']['amount'];

        $signature = GenerateSignature::signSwiftMoneyTransfer($transferReference,$transferDate,$sourceAccount,$destinationAccount,$transferAmount);

        $response = JengaApi::post($endurl,$requestBody,$signature);

        return $response;
    }

    //move money via EFT
    public function moveMoneyViaEft(Request $request){
        $data = $request->toArray();
        $requestBody = $request->all();
        $endurl =  'transaction/v2/remittance';

        $transferReference= $data['transfer']['reference'];
        $sourceAccount= $data['source']['accountNumber'];
        $destinationAccount = $data['destination']['accountNumber'];
        $transferAmount= $data['transfer']['amount'];
        $destinationBankCode = $data['destination']['bankCode'];

        $signature = GenerateSignature::signEftMoneyTransfer($transferReference,$sourceAccount,$destinationAccount,$transferAmount,$destinationBankCode);

        $response = JengaApi::post($endurl,$requestBody,$signature);

        return $response;



    }

    //method to move money via pesalink to a bank account
    public function moveMoneyViaPesaLinkToBank(Request $request){
        $data = $request->toArray();
        $requestBody = $request->all();
        $endurl =  'transaction/v2/remittance';

        $transferAmount= $data['transfer']['amount'];
        $transferCurrencyCode= $data['transfer']['currencyCode'];
        $transferReference= $data['transfer']['reference'];
        $destinationName= $data['destination']['name'];
        $sourceAccount= $data['source']['accountNumber'];

        $signature = GenerateSignature::signPesalinkToBankMoneyTransfer($transferAmount,$transferCurrencyCode,$transferReference,$destinationName,$sourceAccount);

        $response = JengaApi::post($endurl,$requestBody,$signature);
        
        return $response;


    }

    //method to move money via pesalink to a mobile account
    public function moveMoneyViaPesaLinkToMobile(Request $request){
        $data = $request->toArray();
        $requestBody = $request->all();
        $endurl =  'transaction/v2/remittance';

        $transferAmount= $data['transfer']['amount'];
        $transferCurrencyCode= $data['transfer']['currencyCode'];
        $transferReference= $data['transfer']['reference'];
        $destinationName= $data['destination']['name'];
        $sourceAccount= $data['source']['accountNumber'];

        $signature = GenerateSignature::signPesalinkToMobileMoneyTransfer($transferAmount,$transferCurrencyCode,$transferReference,$destinationName,$sourceAccount);

        $response = JengaApi::post($endurl,$requestBody,$signature);

        return $response;


    }

    //method to query pesalink account
    public function pesaLinkInqury(Request $request){
        $endurl =  'transaction/v2/pesalink/inquire';
        $mobileNumber = $request->input('mobileNumber');

        $requestBody = array();
        $requestBody['mobileNumber'] = $mobileNumber;

        $response = JengaApi::postInquiry($endurl,$requestBody);

        return $response;

    }

    //method to do credit scoring
    public function checkCreditScore(Request $request){
        $data = $request->toArray();
        $requestBody = $request->all();
        $endurl = 'customer/v2/creditinfo';

        $dateOfBirth = $data['customer'][0]['dateOfBirth'];
        $merchantCode =env('merchantCode');
        $documentNumber = $data['customer'][0]['identityDocument']['documentNumber'];

        $signature = GenerateSignature::signCreditScore($dateOfBirth,$merchantCode,$documentNumber);

        $response = JengaApi::post($endurl,$requestBody,$signature);

        return $response;
    }


    //get forex rates
    public function getForexRates(Request $request){
        $countryCode = $request->input('countryCode');
        $currencyCode = $request->input('currencyCode');

        $endurl = 'transaction/v2/foreignexchangerates';

        $requestBody = array();
        $requestBody['countryCode'] = $countryCode;
        $requestBody['currencyCode'] = $currencyCode;

        $response = JengaApi::postInquiry($endurl,$requestBody);

        return $response;
    }


    public function signAccountBalance($countryCode,$accountNo){

        $plaintext = $countryCode.$accountNo;

        $fp = fopen(env('PRIVATE_KEY'), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key);

        openssl_sign($plaintext,$signature,$pkeyid,OPENSSL_ALGO_SHA256);

        return $signature;

    }
}
