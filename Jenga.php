<?php

namespace App\Helpers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use anlutro\LaravelSettings\Facade as Setting;


class  JengaApi {

    public static function generateToken(){
        $baseUrl = env('JENGA_ENDPOINT');
        $password = env('JENGA_PASSWORD');
        $username = env('JENGA_USERNAME');
        $key = env("JENGA_KEY");

        $requestBody = [
            'username' => $username,
            'password' => $password
        ];

        $client = new Client();

        try{
            $response = $client->post($baseUrl.'identity/v2/token',[
                'headers' => [
                    'Authorization' => 'Basic '.$key,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                'form_params' => $requestBody

            ]);
            return json_decode((string) $response->getBody(), true);


        } catch(BadResponseException $exception) {

            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);

        }


    }

    //post to end point for requests
    public static function post($endurl,$requestBody,$signature){
        $client = new Client();
        $baseUrl = env('JENGA_ENDPOINT');
        $token = Setting::get('api-token.token');

        try{
            $response = $client->post($baseUrl.$endurl,[
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                    'Content-Type' => 'application/json',
                    'signature' =>  base64_encode($signature)
                ],
                'json' => $requestBody
            ]);

            return json_decode((string) $response->getBody(), true);

        }catch (BadResponseException $exception){

            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }

    }

    public static function get($endpoint,$signature)
    {
        $client = new Client();
        $baseUrl = env('JENGA_ENDPOINT');
        $token = Setting::get('api-token.token');
        try {
            $response = $client->get($baseUrl.$endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                    'Content-Type' => 'application/json',
                    'signature' => base64_encode($signature)
                ]
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (BadResponseException $exception)
        {
            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }
    }

    //specific method for the Send Money Inquiry
    public static function postInquiry($endurl,$requestBody){
        $client = new Client();
        $baseUrl = env('JENGA_ENDPOINT');
        $token = Setting::get('api-token.token');

        try{
            $response = $client->post($baseUrl.$endurl,[
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody
            ]);

            return json_decode((string) $response->getBody(), true);

        }catch (BadResponseException $exception){

            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }

    }
}
