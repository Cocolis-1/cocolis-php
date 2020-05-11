<?php

/**
 * Cocolis API Class
 * API Documentation: https://stoplight.io/p/docs/gh/Cocolis-1/cocolis-api
 * Class Documentation: https://github.com/Cocolis-1/cocolis-php/
 *
 * @author Alexandre BETTAN
 * @author Sebastien Fieloux
 */

namespace Cocolis\API;

class Client
{
    // Local informations

    private $_app_id;
    private $_password;
    private $_live;

    // Returned by the API
    private $_access_token;
    private $_client;
    private $_expiry;
    private $_uid;

    const API_SANDBOX = "https://staging-api.cocolis.fr/api/v1/"; //  Test environment during your implementation
    const API_PROD = "https://api.cocolis.fr/api/v1/"; // Online environment (in production, be careful what you do with this)

    public function getAppId()
    {
        return $this->_app_id;
    }

    public function isLive()
    {
        return $this->_live;
    }

    public function getAccessToken()
    {
        return $this->_access_token;
    }

    public function getClient()
    {
        return $this->_client;
    }

    public function getExpiry()
    {
        return $this->_expiry;
    }

    public function getUID()
    {
        return $this->_uid;
    }

    public function setAppId(string $app_id)
    {
        $this->_app_id = $app_id;
    }


    public function setPassword(string $password)
    {
        $this->_password = $password;
    }

    public function setLive(bool $live)
    {
        $this->_live = $live;
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
    }

    // Connect to the API
    public function signIn()
    {
        if ($this->isLive()) {
            $client = new \GuzzleHttp\Client(['base_uri' => self::API_PROD]);
        } else {
            $client = new \GuzzleHttp\Client(['base_uri' => self::API_SANDBOX]);
        }

        $headers = ['Content-Type' => 'application/json'];
        $body = ['app_id' => $this->_app_id, 'password' => $this->_password];
        $res = $client->request('POST', 'app_auth/sign_in', $headers, $body);

        if ($res->getStatusCode() == 200) {
            return $res->getHeaders();
        }
        
        return false;
    }
}
