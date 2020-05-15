<?php

/**
 * Cocolis API Class
 * API Documentation: https://doc.cocolis.fr
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
  private static $_live = false;

  private static $_client;
  private static $_http_client;

  // Returned by the API
  private static $_access_token;
  private static $_expiry;
  private static $_uid;

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

  public static function getClient(array $auth = array())
  {
    if (!static::$_client) {
      static::$_client = static::create($auth);
    }

    return static::$_client;
  }

  public function getHttpClient()
  {
    return static::$_http_client;
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

  public static function setHttpClient($http_client)
  {
    self::$_http_client = $http_client;
  }

  public static function setClient($client)
  {
    self::$_client = $client;
  }

  public static function setAccessToken(string $token)
  {
    self::$_access_token = $token;
  }

  public static function setExpiry(string $expiry)
  {
    self::$_expiry = $expiry;
  }

  public static function setUid(string $uid)
  {
    self::$_uid = $uid;
  }

  public static function getExpiry()
  {
    return self::$_expiry;
  }

  public static function getUID()
  {
    return self::$_uid;
  }

  // Initialize the connection to the api
  public static function create(array $auth)
  {
    $client = new static();

    if (!isset($auth['app_id']) || empty($auth['app_id'])) {
      throw new \InvalidArgumentException('Key app_id is missing');
    } elseif (!isset($auth['password']) || empty($auth['password'])) {
      throw new \InvalidArgumentException('Key password is missing');
    }

    if (isset($auth['live'])) {
      self::setLive($auth['live']);
    }

    self::setAppId($auth['app_id']);
    self::setPassword($auth['password']);

    $url = self::isLive() ? self::API_PROD : self::API_SANDBOX;

    self::setHttpClient(new \GuzzleHttp\Client(['base_uri' => $url]));
    self::setClient($client);

    return $client;
  }

  // Connect to the API
  public function signIn()
  {
    try {
      $res = $this->call('app_auth/sign_in', 'POST', ['app_id' => self::getAppId(), 'password' => self::getPassword()]);
    } catch (\Throwable $th) {
      return false;
    }

    self::setAccessToken($res->getHeader('Access-Token')[0]);
    self::setClient($res->getHeader('Client')[0]);
    self::setExpiry($res->getHeader('Expiry')[0]);
    self::setUid($res->getHeader('Uid')[0]);

    return array('access-token' => self::getAccessToken(), 'client' => self::getClient(), 'expiry' => self::getExpiry(), 'uid' => self::getUID());
  }

  public function call($url, $method = 'GET', $body = array())
  {
    return $this->getHttpClient()->request($method, $url, ['json' => $body]);
  }
}
