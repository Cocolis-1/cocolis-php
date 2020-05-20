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

class RideClient extends AbstractClient
{
  public $_rest_path = 'rides';

  private $_seller_tracking;
  private $_buyer_tracking;

  
  const URL_SANDBOX = "https://sandbox.cocolis.fr/";
  const URL_PROD = "https://cocolis.fr/";

  public function getSellerTracking()
  {
    return $this->_seller_tracking;
  }


  public function getBuyerTracking()
  {
    return $this->_buyer_tracking;
  }

  public function setSellerTracking(string $code)
  {
    $this->_seller_tracking = $code;
  }


  public function setBuyerTracking(string $code)
  {
    $this->_buyer_tracking = $code;
  }

  public function getBuyerURL()
  {
    if (Client::isLive()) {
      return self::URL_PROD . 'rides/buyer/' . $this->getBuyerTracking();
    }

    return self::URL_SANDBOX . 'rides/buyer/' . $this->getBuyerTracking();
  }

  public function getSellerURL()
  {
    if (Client::isLive()) {
      return self::URL_PROD . 'rides/seller/' . $this->getSellerTracking();
    }

    return self::URL_SANDBOX . 'rides/seller/' . $this->getSellerTracking();
  }

  public function mine()
  {
    return $this->hydrate(json_decode($this->getCocolisClient()->callAuth($this->getRestPath('mine'))->getBody(), true)['rides']);
  }

  public function canMatch(string $zipfrom, string $zipto, float $volume, int $value = null)
  {
    return $this->hydrate(json_decode(
      $this->getCocolisClient()->callAuth(
        $this->getRestPath('can_match'),
        'POST',
        ['from' => ['postal_code' => $zipfrom], 'to' => ['postal_code' => $zipto], 'volume' => $volume, 'content_value' => $value]
      )->getBody(),
      true
    ));
  }

  public function createRide(array $params)
  {
    $res = $this->hydrate(json_decode($this->getCocolisClient()->callAuth('rides', 'POST', array('ride' => $params))->getBody(), true));
    $this->setSellerTracking($res->seller_tracking);
    $this->setBuyerTracking($res->buyer_tracking);
    return $res;
  }
}
