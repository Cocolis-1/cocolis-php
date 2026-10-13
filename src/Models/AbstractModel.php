<?php

/**
 * Cocolis API Class
 * API Documentation: https://doc.cocolis.fr
 * Class Documentation: https://github.com/Cocolis-1/cocolis-php/
 *
 * @author Cocolis
 */

namespace Cocolis\Api\Models;

abstract class AbstractModel
{
  private $_data;
  private $_client;

  public function __construct($data, $client)
  {
    $this->_data = $data;
    $this->_client = $client;
  }

  public function getBaseURL()
  {
    return $this->_client->getBaseURL();
  }

  public function __get(string $get)
  {
    if (is_object($this->_data)) {
      if (isset($this->_data->{$get})) {
        $return = $this->_data->{$get};
      } else {
        $return = null;
      }
    } else {
      // trigger_error() with E_USER_ERROR is deprecated as of PHP 8.4.
      throw new \UnexpectedValueException('Data is no object!');
    }
    return $return;
  }
}
