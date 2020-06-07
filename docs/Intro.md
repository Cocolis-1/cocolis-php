# Introduction

Cette librairie a été concu pour aider les développeurs à intégrer les fonctionnalités de **Cocolis** dans leur applications sans gérer les appels vers l'API.

Elle inclut toutes les fonctionnalités globales tel que **l'authentification**, la gestion des **Rides** et des **Webhooks**.

Vous pouvez signaler des bugs sur cette [page](https://github.com/Cocolis-1/cocolis-php/issues).

# Principe général

> Avant toute chose, vous pouvez intégrer notre librairie à votre application grâce à 
> **composer** avec la commande suivante : 
> `composer require cocolis/php`

La librairie est essentiellement constituée de classes dont l'une des plus importantes, **Client.php**. Celle-ci permet d'instancier l'authentification et de récupérer les tokens nécessaires pour chaque appel API.

Il existe deux autres classes, **RideClient.php** et **WebhookClient.php**. Celles-ci permettent d'effectuer tous les appels pour créer une Ride, vérifier la compatibilité d'un trajet, etc ... 

Leur utilisatation sera détaillée dans la suite de la documentation.

# Documentation API

Le principe de la librairie étant essentiellement basé sur la **documentation officielle de l'API**, vous pouvez la retrouver **[ici](https://doc.cocolis.fr)**.

## Authentification

Avec la librairie, vous pouvez vous authentifier facilement de cette façon et **une seule fois** :

```php
$client = Client::create(array(
    'app_id' => 'mon_appid',
    'password' => 'mon_mot_de_passe',
    'live' => false // Permet de choisir l'environnement
  ));
$client->signIn();
```

Vous n'avez plus qu'à utiliser l'objet `$client` pour effectuer un appel.

Par exemple, pour **vérifier la disponibilité** d'une Ride :

```php
$result = $client->getRideClient()->canMatch(75000, 31400, 10);
```

Une fois authentifié, vous pouvez effectuer des **requêtes annexes** à l'API de cette façon :

```php
$client->callAuthentificated('app_auth/validate_token', 'GET', $params);
```

Dans cet exemple, `app_auth/validate_token` est équivalent à faire un appel vers : 
`{cocolis_domain}/api/v1/app_auth/validate_token` de type `GET` avec le `$params` fourni sous la forme **d'un array**.

## Validation du token

A chaque début de communication avec l'API, vous devez vérifier si vos tokens sauvés et précedemment générés lors du `$client->signIn()` sont toujours valides. Pour cela, vous devez faire l'appel suivant :

```php
$client->validateToken($authinfo);
```

Le `$authinfo` n'est pas un paramètre obligatoire, il permet de tester la validité d'autres paramètres d'authentification.

L'appel renvoie une réponse trouvable dans le `body` avec `"success": boolean` qui permet de déterminer la validité des informations d'authentification.

## Environnements

Il existe **deux environnements**, l'environnement de test (**sandbox**) et l'environnement de **production**, vous pouvez en savoir plus [ici](https://doc.cocolis.fr/docs/cocolis-api/docs/Installation-et-utilisation/01-Environnements.md).

