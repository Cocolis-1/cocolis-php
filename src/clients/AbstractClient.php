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
    return json_decode(json_encode($array));
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

  public function create(array $params)
  {
    return $this->hydrate(json_decode($this->getCocolisClient()->callAuthentificated($this->getRestPath(''), 'POST', $params)->getBody(), true));
  }

  public function update(array $params, string $id)
  {
    return $this->hydrate(json_decode($this->getCocolisClient()->callAuthentificated($this->getRestPath('/') . $id, 'PUT', $params)->getBody(), true));
  }

  public function getAll()
  {
    return $this->hydrate(json_decode($this->getCocolisClient()->callAuthentificated($this->getRestPath(''), 'GET')->getBody(), true));
  }

  public function get(string $id)
  {
    return $this->hydrate(json_decode($this->getCocolisClient()->callAuthentificated($this->getRestPath('/' . $id), 'GET')->getBody(), true));
  }

  public function remove(string $id)
  {
    return json_decode($this->getCocolisClient()->callAuthentificated($this->getRestPath('/') . $id, 'DELETE')->getBody(), true);
  }
}
