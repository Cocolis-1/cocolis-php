<?php

/**
 * Cocolis API Class
 * API Documentation: https://doc.cocolis.fr
 * Class Documentation: https://github.com/Cocolis-1/cocolis-php/
 *
 * @author Cocolis
 */

namespace Cocolis\Api\Clients;

class RideClient extends AbstractClient
{
  public $_rest_path = 'rides';

  private $_seller_tracking;
  private $_buyer_tracking;

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
    return $this->getBaseURL() . 'rides/buyer/' . $this->getBuyerTracking();
  }

  public function getSellerURL()
  {
    return $this->getBaseURL() . 'rides/seller/' . $this->getSellerTracking();
  }

  public function mine()
  {
    return $this->hydrate(json_decode($this->getCocolisClient()->callAuthentificated($this->getRestPath('mine'))->getBody(), true)['rides']);
  }

  public function canMatch(string $zipfrom, string $zipto, float $volume, int $value = null)
  {
    $params = ['from' => ['postal_code' => $zipfrom], 'to' => ['postal_code' => $zipto], 'volume' => $volume];
    if (!empty($value)) {
      $params['content_value'] = $value;
    }

    return $this->hydrate(json_decode(
      $this->getCocolisClient()->callAuthentificated(
        $this->getRestPath('can_match'),
        'POST',
        $params
      )->getBody(),
      true
    ));
  }

  public function createRide(array $params)
  {
    $res = $this->hydrate(json_decode($this->getCocolisClient()->callAuthentificated('rides', 'POST', array('ride' => $params))->getBody(), true));
    $this->setSellerTracking($res->seller_tracking);
    $this->setBuyerTracking($res->buyer_tracking);
    return $res;
  }
}
