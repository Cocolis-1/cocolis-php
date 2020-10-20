<?php

namespace Tests\Api;

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


  public function testSignInWithWrongCredentials()
  {
    $this->expectException(\Cocolis\Api\Errors\UnauthorizedException::class);

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
      'Access-Token' => 'nBfCK2mL3rp83hSjhUFLYg',
      'client' => 'g5z8dF58MvI4t5zoQzX9xA',
      'Expiry' => '1604319132',
      'Uid' => 'e0611906'
    ), $result);
  }

  public function testAppIdException()
  {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Key app_id is missing');
    Client::create(array(
      'app_id' => '',
      'password' => 'test'
    ));
  }

  public function testAuthInfo()
  {
    $this->assertEquals(array(
      'Access-Token' => 'nBfCK2mL3rp83hSjhUFLYg',
      'client' => 'g5z8dF58MvI4t5zoQzX9xA',
      'Expiry' => '1604319132',
      'Uid' => 'e0611906'
    ), Client::getCurrentAuthInfo());
  }

  public function testPasswordException()
  {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Key password is missing');
    Client::create(array(
      'app_id' => 'test',
      'password' => ''
    ));
  }

  public function testTokenValid()
  {
    $client = Client::create(array(
      'app_id' => 'e0611906',
      'password' => 'sebfie',
      'live' => false
    ));
    $result = $client->validateToken(array(
      'Access-Token' => 'nBfCK2mL3rp83hSjhUFLYg',
      'client' => 'wFPP7k01OacAzC-tXni-fA',
      'Expiry' => '1604318220',
      'Uid' => 'e0611906'
    ));
    $this->assertEquals($result, true);
  }

  public function testTokenNoArgs()
  {
    $client = Client::create(array(
      'app_id' => 'e0611906',
      'password' => 'sebfie',
      'live' => false
    ));
    // Clear auth
    $client->setAuth(null);

    // No arguments
    $this->expectException(InvalidArgumentException::class);
    $client->validateToken();
  }


  public function testTokenInvalid()
  {
    $this->expectException(\Cocolis\Api\Errors\UnauthorizedException::class);
    $this->expectExceptionMessage('{"success":false,"errors":["Mot de passe ou identifiant invalide."]}');

    $client = Client::create(array(
      'app_id' => 'e0611906',
      'password' => 'sebfie',
      'live' => false
    ));

    // Invalid params
    $result = $client->validateToken(["Uid" => "e0611906", "Access-Token" => "thisisnotavalidtoken", "client" => "HLSmEW1TIDqsSMiwuKjnQg", "Expiry" => "1590748027"]);
    $this->assertEquals($result, false);
  }

  public function testTokenNoAuth()
  {
    $client = Client::create(array(
      'app_id' => 'e0611906',
      'password' => 'sebfie',
      'live' => false
    ));
    $client->signIn();
    $result = $client->validateToken();
    $this->assertNotEmpty($result);
  }
}
