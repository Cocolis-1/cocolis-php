<?php

/**
 * Cocolis API Class
 * API Documentation: https://doc.cocolis.fr
 * Class Documentation: https://github.com/Cocolis-1/cocolis-php/
 *
 * @author Cocolis
 */

namespace Cocolis\Api\Clients;

use Exception;

class RideClient extends AbstractClient
{
  public $_rest_path = 'rides';
  public $_model_class = 'Cocolis\Api\Models\Ride';

  public function getBuyerURL(string $id)
  {
    return $this->getBaseURL() . 'rides/buyer/' . $this->get($id)->buyer_tracking;
  }

  public function getSellerURL(string $id)
  {
    return $this->getBaseURL() . 'rides/seller/' . $this->get($id)->seller_tracking;
  }

  public function mine()
  {
    return $this->hydrate(json_decode($this->getCocolisClient()->callAuthentificated($this->getRestPath('mine'))->getBody(), true));
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

  public function remove(string $id)
  {
    throw new Exception('This feature is not accessible in this Class');
  }

  public function update(array $params, string $id)
  {
    throw new Exception('This feature is not accessible in this Class');
  }

  public function getAll()
  {
    throw new Exception('This feature is not accessible in this Class');
  }
}
