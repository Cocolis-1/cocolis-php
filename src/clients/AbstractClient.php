<?php

/**
 * Cocolis API Class
 * API Documentation: https://doc.cocolis.fr
 * Class Documentation: https://github.com/Cocolis-1/cocolis-php/
 *
 * @author Cocolis
 */

namespace Cocolis\Api\Clients;

use Cocolis\Api\Client;
use PhpCsFixer\ConfigurationException\InvalidConfigurationException;

abstract class AbstractClient
{
  private $_cocolis_client;
  public $_rest_path;

  public function __construct(\Cocolis\Api\Client $cocolisClient)
  {
    $this->_cocolis_client = $cocolisClient;
  }

  public function hydrate(array $array)
  {
    $stdClassArray = json_decode(json_encode($array));
    if (is_array($stdClassArray)) {
      $result = array();
      foreach($stdClassArray as $item)
      {
        array_push($result, new $this->_model_class($item, $this));
      }
    }
    if (is_object($stdClassArray)) {
      $result = new $this->_model_class($item, $this);
    }
    return $result;
  }

  public function getCocolisClient()
  {
    return $this->_cocolis_client;
  }

  public function getBaseURL()
  {
    return Client::isLive() ? Client::FRONTEND_PROD : Client::FRONTEND_SANDBOX;
  }

  public function getRestPath(string $path)
  {
    if (empty($this->_rest_path)) {
      throw new InvalidConfigurationException('The child class shoud defined $_rest_path');
    }

    return $this->_rest_path . '/' . $path;
  }
}
