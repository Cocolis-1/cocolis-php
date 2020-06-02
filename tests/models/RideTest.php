<?php

namespace Tests\Api;

use Cocolis\Api\Client;
use Cocolis\Api\Models\Ride;
use PHPUnit\Framework\Error\Error;

class RideTest extends CocolisTest
{
  public function testGetBuyerURL()
  {
    $client = new Client();
    $model = $client->getRideClient()->get(1)->getBuyerURL();
    $this->assertNotEmpty($model);
  }

  public function testGetSellerURL()
  {
    $client = new Client();
    $model = $client->getRideClient()->get(1)->getSellerURL();
    $this->assertNotEmpty($model);
  }

  public function testObjectException()
  {
    $this->expectException(Error::class);
    $this->expectExceptionMessage('Data is no object!');
    $client = new Client();
    $ride = new Ride('toto', $client);
    $ride->my_key;
  }

  public function testArray()
  {
    $client = new Client();
    $model = $client->getRideClient()->hydrate(array('toto'));
    var_dump($model);
  }
}
