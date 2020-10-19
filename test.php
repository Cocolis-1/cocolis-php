<?php

require_once __DIR__ . '/vendor/autoload.php';

use Cocolis\Api\Client;

$client = Client::create(array(
  'app_id' => 'e0611906',
  'password' => 'sebfie',
  'live' => false // Permet de choisir l'environnement
));
$client->signIn();
$response = $client->getRideClient()->canMatch(75001, 13210, 10);
var_dump($response);

var_dump('end');