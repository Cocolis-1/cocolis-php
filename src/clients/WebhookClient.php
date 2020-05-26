<?php

/**
 * Cocolis API Class
 * API Documentation: https://doc.cocolis.fr
 * Class Documentation: https://github.com/Cocolis-1/cocolis-php/
 *
 * @author Cocolis
 */

namespace Cocolis\Api;

use Cocolis\Api\Clients\AbstractClient;

class WebhookClient extends AbstractClient
{
  public $_rest_path = 'applications/';

  public function create(string $event, string $url, bool $active)
  {
    return $this->hydrate(json_decode($this->getCocolisClient()->callAuthentificated($this->getRestPath('webhooks'), 'POST', array('event' => $event, 'url' => $url, 'active' => $active))->getBody(), true));
  }

  public function getAll()
  {
    return $this->hydrate(json_decode($this->getCocolisClient()->callAuthentificated($this->getRestPath('webhooks'), 'GET')->getBody(), true));
  }

  public function get(string $id)
  {
    return $this->hydrate(json_decode($this->getCocolisClient()->callAuthentificated($this->getRestPath('webhooks/' . $id), 'GET')->getBody(), true));
  }

  public function remove(string $id)
  {
    return json_decode($this->getCocolisClient()->callAuthentificated($this->getRestPath('webhooks'), 'DELETE', ['id' => $id])->getBody(), true);
  }
}
