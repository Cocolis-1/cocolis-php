<?php

namespace Tests\Api;

use PHPUnit\Framework\TestCase;
use Cocolis\Api\Client;
use InvalidArgumentException;
use Tests\Api\CocolisTest;

class ClientTest extends CocolisTest
{
 
  public function testEmptyClient()
  {
    $client = Client::getClient(array(
      'app_id' => 'e0611906',
      'password' => 'sebfie',
      'live' => false
    ));
    $this->assertNotEmpty($client);
  }


 public function testContent()
  {
    $this->assertEquals('https://sandbox-api.cocolis.fr/api/v1/', Client::API_SANDBOX);
    $this->assertEquals('https://api.cocolis.fr/api/v1/', Client::API_PROD);
  }

  public function testClient()
  {
    $client = Client::create(array(
      'app_id' => 'e0611906',
      'password' => 'sebfie',
      'live' => false
    ));
    $this->assertNotEmpty($client);
  }


  public function testSignIn()
  {
    $client = Client::create(array(
      'app_id' => 'e0611906',
      'password' => 'notsebfie',
      'live' => false
    ));
    $result = $client->signIn();
    $this->assertEquals($result, false);
  }


  public function testStaticCreate()
  {
    $client = Client::create(array(
      'app_id' => 'e0611906',
      'password' => 'sebfie',
      'live' => false
    ));
    $result = $client->signIn();
    $this->assertEquals(array(
      'access-token' => 'Jy64iEiJ4vUgtp8TqhkTkQ',
      'client' => 'HLSmEW1TIDqsSMiwuKjnQg',
      'expiry' => '1590748027',
      'uid' => 'e0611906'
    ), $result);
  }

  public function testAppIdException()
  {
    $this->expectException(InvalidArgumentException::class);
    Client::create(array(
      'app_id' => '',
      'password' => 'test'
    ));
  }

  public function testPasswordException()
  {
    $this->expectException(InvalidArgumentException::class);
    Client::create(array(
      'app_id' => 'test',
      'password' => ''
    ));
  }
}
