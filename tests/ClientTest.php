<?php

namespace Tests\API;

use PHPUnit\Framework\TestCase;
use Cocolis\Api\Client;

class ClientTest extends TestCase
{
    public function testAPI()
    {
        // After turning on the VCR will intercept all requests
        \VCR\VCR::turnOn();

        // Record requests and responses in cassette file 'example'
        \VCR\VCR::insertCassette('clienttest_api');

        // Following request will be recorded once and replayed in future test runs
        $client = new Client();
        Client::create(array(
            'app_id' => 'e0611906',
            'password' => 'sebfie',
            'live' => false
        ));
        $result = $client->signIn();
        var_dump($result);
        $this->assertNotEmpty($result);

        // To stop recording requests, eject the cassette
        \VCR\VCR::eject();

        // Turn off VCR to stop intercepting requests
        \VCR\VCR::turnOff();
    }
}
