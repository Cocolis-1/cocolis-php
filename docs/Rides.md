# Rides

Une Ride correspond à une annonce chez Cocolis.

### Le client

Une fois le client authentifié, vous pouvez intéragir sur les Rides de cette façon :

```php
$client->getRideClient()
```

### Récupérer toutes les Rides

Vous pouvez récupérer toutes les Rides créées sous la forme d'un tableau :

```php
$rides = $client->getRideClient()->mine();
```



### Vérifier la possibilité de réaliser une Ride

Vérifier si Cocolis sera disponible pour effectuer la livraison pour un trajet donné entre 2 points, avec le prix de l'assurance inclus.

```php
$match = $client->getRideClient()->canMatch($zipfrom, $zipto, $volume, $value);
```

Voici un exemple de réponse en `JSON`:

```json
{
"result":true,
"estimated_prices": {
  "regular":95500,
  "with_insurance":96728
},
"insurance_detail":{
  "amount":150100,
  "conditions_url":"https://sandbox.cocolis.fr/assurance/conditions-assurance-optionnelle-jusqu-a-3000.pdf"
},
"rider_count":0
}
```

Cette réponse peut être ensuite exploitée de cette façon avec la librairie `PHP` :

```php
$regular_price = $match->estimated_prices->regular;
```

### Créer une Ride

Vous pouvez créer une Ride comme dans l'exemple présenté ci-dessous (pour en savoir plus sur les paramètres cliquez [ici](https://doc.cocolis.fr/docs/cocolis-api/docs/models/ride/ride-create.json)) :

```php
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
    ...
];
$client = $client->getRideClient();
$ride = $client->create($params);
```
