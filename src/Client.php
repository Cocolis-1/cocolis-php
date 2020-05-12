<?php

/**
 * Cocolis API Class
 * API Documentation: https://stoplight.io/p/docs/gh/Cocolis-1/cocolis-api
 * Class Documentation: https://github.com/Cocolis-1/cocolis-php/
 *
 * @author Cocolis
 */

namespace Cocolis\Api;

class Client
{
    // Local informations
    private static $_app_id;
    private static $_password;
    private static $_live;

    private static $_client;

    // Returned by the API
    private $_access_token;
    private $_expiry;
    private $_uid;

    const API_SANDBOX = "https://sandbox-api.cocolis.fr/api/v1/"; //  Test environment during your implementation
    const API_PROD = "https://api.cocolis.fr/api/v1/"; // Online environment (in production, be careful what you do with this)


    public static function isLive()
    {
        return self::$_live;
    }

    public static function getAppId()
    {
        return self::$_app_id;
    }

    public static function getPassword()
    {
        return self::$_password;
    }

    public static function getAccessToken()
    {
        return self::$_access_token;
    }

    public static function getHttpClient()
    {
        return self::$_client;
    }

    public static function setAppId(string $app_id)
    {
        self::$_app_id = $app_id;
    }


    public static function setPassword(string $password)
    {
        self::$_password = $password;
    }

    public static function setLive(bool $live)
    {
        self::$_live = $live;
    }

    public static function setClient($client)
    {
        self::$_client = $client;
    }

    public function getExpiry()
    {
        return $this->_expiry;
    }

    public function getUID()
    {
        return $this->_uid;
    }


    // Initialize the connection to the api
    public static function create(array $auth)
    {
        // Set array of values for the class
        if (isset($auth['app_id'])) {
            self::setAppId($auth['app_id']);
        }

        if (isset($auth['password'])) {
            self::setPassword($auth['password']);
        }

        if (isset($auth['live'])) {
            self::setLive($auth['live']);
        }

        if(self::isLive()){
            self::setClient(new \GuzzleHttp\Client(['base_uri' => self::API_PROD]));
        }else{
            self::setClient(new \GuzzleHttp\Client(['base_uri' => self::API_SANDBOX]));
        } 
    }

    // Connect to the API
    public function signIn()
    {
        $res = $this->call('app_auth/sign_in', 'POST', ['app_id' => self::getAppId(), 'password' => self::getPassword()]);

        if ($res->getStatusCode() == 200) {
            return array('access-token' => array_values($res->getHeader('Access-Token'))[0], 'client' => array_values($res->getHeader('Client'))[0], 'expiry' => array_values($res->getHeader('Expiry'))[0], 'uid' => array_values($res->getHeader('Uid'))[0]);
        }

        return false;
    }

    public function call($url, $method = 'GET', $body = array())
    {
        $client = $this->getHttpClient();
        return $client->request($method, $url, ['json' => $body]);
    }
}
