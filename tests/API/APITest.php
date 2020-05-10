<?php

namespace Tests\API;

use PHPUnit\Framework\TestCase;
use Cocolis\Api\Client;

class APITest extends TestCase
{
    public function testAPI()
    {
         // After turning on the VCR will intercept all requests
         \VCR\VCR::turnOn();

         // Record requests and responses in cassette file 'example'
         \VCR\VCR::insertCassette('example');
 
         // Following request will be recorded once and replayed in future test runs
         $client = new Client();
         $client = $client::create(array(
             'app_id' => 'mon_app_id',
             'password' => 'test',
             'live' => false
         ));
         $result = $client->signIn();
         $this->assertNotEmpty($result);
 
         // To stop recording requests, eject the cassette
         \VCR\VCR::eject();
 
         // Turn off VCR to stop intercepting requests
         \VCR\VCR::turnOff();
    }
}
