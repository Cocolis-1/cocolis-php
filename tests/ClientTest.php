<?php

namespace Tests\API;

use PHPUnit\Framework\TestCase;
use Cocolis\Api\Client;

class ClientTest extends TestCase
{
  /**
   * @before
   */
  public function setupSomeFixtures()
  {
    var_dump('I am before test');
  }

  // @TODO Test all getters, setter
  // Getter should return value setted
  // Setters should set values

  public function getContant()
  {
    //@TODO API_SANDBOX should be equal to https://sandbox-api.cocolis.fr/api/v1/
        //@TODO API_PROD should be equal to https://api.cocolis.fr/api/v1/
  }

  public function getClient()
  {
    //@TODO Should create a client with passed data
        //@TODO Should return existing client is already created
  }

  public function testSignIn()
  {
    //@TODO Should call $client->call with right arguments
        // Read : https://phpunit.readthedocs.io/en/9.1/test-doubles.html?highlight=mock

        // @TODO Should return false if the response is not 200
        // @TODO Should return array with right values if response is 200
  }

  public function testStaticCreate()
  {
    // After turning on the VCR will intercept all requests
    \VCR\VCR::turnOn();

    // Record requests and responses in cassette file 'example'
    \VCR\VCR::insertCassette('clienttest_api');

    // Following request will be recorded once and replayed in future test runs
    $client = Client::create(array(
            'app_id' => 'e0611906',
            'password' => 'sebfie',
            'live' => false
        ));
    $result = $client->signIn();

    $this->assertNotEmpty($result);

    // @TODO
    // Vérifier que la réponse est bien égale à (ce que tu as dans la cassette) :
    // array(4) {
    //     'access-token' =>
    //     string(22) "8dX5MJhZ6I5NAxK0-uxLjw"
    //     'client' =>
    //     string(22) "q5ubXwodRenhrX07axaOTA"
    //     'expiry' =>
    //     string(10) "1590568115"
    //     'uid' =>
    //     string(8) "e0611906"
    //   }

    // To stop recording requests, eject the cassette
    \VCR\VCR::eject();

    // Turn off VCR to stop intercepting requests
    \VCR\VCR::turnOff();
  }
}
