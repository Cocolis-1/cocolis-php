<?php

namespace Tests\Api;

use Cocolis\Api\Client;

class WebhookClientTest extends CocolisTest
{
  public function testCreate()
  {
    $client = new Client();
    $client = $client->getWebhookClient();
    $results = $client->create('ride_published', 'https://www.test.com/ride_webhook', true);
    var_dump($results);
    $this->assertNotEmpty($results);
  }

  public function testGetAll()
  {
    $client = new Client();
    $client = $client->getWebhookClient();
    $results = $client->getAll();
    var_dump($results);
    $this->assertNotEmpty($results);
  }

  public function testGet()
  {
    $client = new Client();
    $client = $client->getWebhookClient();
    $results = $client->get('1');
    var_dump($results);
    $this->assertNotEmpty($results);
  }

  public function testRemove()
  {
    $client = new Client();
    $client = $client->getWebhookClient();
    $this->assertEmpty($client->remove('1'));
  }
}
