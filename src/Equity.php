<?php

namespace Osen\Finserve;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class Equity
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

    public static function generateToken()
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
            $response = $client->post($baseUrl."identity/v2/token", [
                "headers" => [
                    "Authorization" => "Basic {$key}",
                    "Content-Type" => "application/x-www-form-urlencoded",
                    ],
                "form_params" => $requestBody

            ]);
            
            $token = json_decode((string) $response->getBody(), true);
            
            self::setToken($token["access_token"]);
        } catch (BadResponseException $exception) {
            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }
    }

    //post to end point for requests
    public static function post($endurl, $requestBody, $signature)
    {
        $client = new Client();
        $baseUrl = self::config("endpoint");
        $token = self::$token;

        try {
            $response = $client->post($baseUrl.$endurl, [
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

    public static function get($endpoint, $signature)
    {
        $client = new Client();
        $baseUrl = self::config("endpoint");
        $token = self::$token;
        try {
            $response = $client->get($baseUrl.$endpoint, [
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
            $response = $client->post($baseUrl.$endurl, [
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
}