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
            ? "https://uat.jengahq.io/"
            : "https://sandbox.jengahq.io/";

        $defaults = array(
            "endpoint"    => $endpoint,
            "username"    => "",
            "password"    => "",
            "key"         => "",
            "private_key" => "file://".__DIR__ . "/privatekey.pem",
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
        return is_null($key) ? self::$configs : self::$configs[$key];
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

    //generate signature fo internaltransfers
    public static function signTransaction()
    {
        $plaintext = implode("", func_get_args());
        $priv_key = self::config("private_key");
        $pkeyid = openssl_pkey_get_private($priv_key);

        openssl_sign($plaintext, $signature, $pkeyid, OPENSSL_ALGO_SHA256);

        return $signature;
    }
}
