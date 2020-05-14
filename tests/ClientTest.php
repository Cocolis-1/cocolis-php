<?php

namespace Tests\Api;

use PHPUnit\Framework\TestCase;
use Cocolis\Api\Client;

class ClientTest extends \Tests\Api\CocolisTest
{
  /**
   * @before
   */
  public function setupSomeFixtures()
  {
    // var_dump('I am before test');
  }

  // @TODO Test all getters, setter
  // Getter should return value setted
  // Setters should set values

  public function getContent()
  {
    $this->assertEquals('https://sandbox-api.cocolis.fr/api/v1/', Client::API_SANDBOX);
    $this->assertEquals('https://api.cocolis.fr/api/v1/', Client::API_PROD);
  }

  public function getClient()
  {
    //@TODO Should create a client with passed data
    //@TODO Should return existing client is already created
    $client = Client::create(array(
            'app_id' => 'e0611906',
            'password' => 'sebfie',
            'live' => false
        ));
    $this->assertNotEmpty($client);
  }

  public function testSignIn()
  {
    //@TODO Should call $client->call with right arguments
    // Read : https://phpunit.readthedocs.io/en/9.1/test-doubles.html?highlight=mock

    // @TODO Should return false if the response is not 200
    // @TODO Should return array with right values if response is 200
    $this->assertEquals(true, true);
  }

  public function testStaticCreate()
  {
    // Following request will be recorded once and replayed in future test runs
    $client = Client::create(array(
            'app_id' => 'e0611906',
            'password' => 'sebfie',
            'live' => false
        ));
    $result = $client->signIn();
    $this->assertEquals(array(
      'access-token' => 'sITqhzkMojX_BARZQdl9Ww',
      'client' => '6UH8rnh-fCoLdkcBNI7EBQ',
      'expiry' => '1590656678',
      'uid' => 'e0611906'
    ), $result);
  }
}
