<?php

namespace Tests\Api;

use Cocolis\Api\Client;

class WebhookClientTest extends CocolisTest
{
  public function testCreate()
  {
    $client = new Client();
    $results = $client->getWebhookClient()->create(['event' => 'ride_published', 'url' => 'https://www.test.com/ride_webhook', 'active' => true]);
    $this->assertNotEmpty($results);
  }

  public function testUpdate()
  {
    $client = new Client();
    $results = $client->getWebhookClient()->update(['event' => 'offer_accepted', 'url' => 'https://www.test.com/ride_webhook', 'active' => true], '1');
    $this->assertNotEmpty($results);
  }

  public function testGetAll()
  {
    $client = new Client();
    $results = $client->getWebhookClient()->getAll();
    $this->assertNotEmpty($results);
  }

  public function testGet()
  {
    $client = new Client();
    $results = $client->getWebhookClient()->get('1');
    $this->assertNotEmpty($results);
  }

  public function testRemove()
  {
    $client = new Client();
    $results = $client->getWebhookClient()->remove('1');
    $this->assertNotEmpty($results);
  }
}
