<?php

namespace Tests\Api;

use Cocolis\Api\Client;

class RideClientTest extends CocolisTest
{
  public function testMine()
  {
    $client = new Client();
    $rides = $client->getRideClient()->mine();
    $this->assertNotEmpty($rides);
  }

  public function testMatch()
  {
    $client = new Client();
    $results = $client->getRideClient()->canMatch(75015, 31400, 10, 150100);
    $this->assertNotEmpty($results);
  }

  public function testCreate()
  {
    $client = new Client();
    $params = [
      "description" => "Carcassonne vers toulu",
      "from_lat" => 43.212498,
      "to_lat" => 43.599120,
      "from_address" => "Carcassonne",
      "to_address" => "Toulouse",
      "from_lng" => 2.350351,
      "to_lng" => 1.444391,
      "from_is_flexible" => true,
      "from_pickup_date" => "2020-06-13T14:21:21+00:00",
      "to_is_flexible" => true,
      "to_pickup_date" => "2020-06-13T14:21:21+00:00",
      "is_passenger" => false,
      "is_packaged" => false,
      "price" => 57000,
      "volume" => 15,
      "environment" => "objects",
      "from_need_help" => false,
      "from_need_help_floor" => "0",
      "from_need_help_elevator" => false,
      "from_need_help_furniture_lift" => false,
      "to_need_help" => false,
      "to_need_help_floor" => 0,
      "to_need_help_elevator" => false,
      "to_need_help_furniture_lift" => false,
      "rider_extra_information" => "Extra informations",
      "photos" => [],
      "ride_objects_attributes" => [
        [
          "title" =>
          "Canapé", "qty" => 1,
          "format" => "xxl"
        ]
      ],
      "ride_delivery_information_attributes" => [
        "from_address" => "14 rue des fleurs",
        "from_postal_code" => "69000",
        "from_city" => "Lyon",
        "from_country" => "FR",
        "from_contact_name" => "John Smith",
        "from_contact_email" => "john.smith@gmail.com",
        "from_contact_phone" => "06 01 02 02 02",
        "from_extra_information" => "test",
        "to_address" => "19 rue des champignons",
        "to_postal_code" => "75000",
        "to_city" => "Paris",
        "to_country" => "FR",
        "to_contact_name" => "John Doe",
        "to_contact_email" => "john.doe@gmail.com",
        "to_contact_phone" => "06 07 08 06 09"
      ]
    ];
    $client = $client->getRideClient();
    $results = $client->createRide($params);
    $this->assertNotEmpty($results, $client->getBuyerURL(), $client->getSellerURL());

    // Test live version
    Client::setLive(true);
    $this->assertNotEmpty($client->getBuyerURL(), $client->getSellerURL());
  }
}
