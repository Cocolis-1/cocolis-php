# Implémentation standard

L'implémentation de Cocolis se fait en général en respectant ces étapes :

## 1. Principe général

Voici un schéma des échanges entre votre site et l'API Cocolis :

![Schéma principe cocolis](https://res.cloudinary.com/cocolis-prod/image/upload/v1587135214/Sch%C3%A9mas_API_Cocolis_fuu9hc.svg)

Le prélèvement du montant de la livraison est effectué sur le compte MangoPay associé à votre compte Cocolis. Il est déclenché lors de la confirmation de la livraison par le transporteur, c’est à dire lorsque la “Ride” passe en statut “completed”.

## 2. Demander un compte développeur

Vous pouvez demander la création d'un compte développeur en remplissant [ce formulaire](https://docs.google.com/forms/d/e/1FAIpQLSe9DZntip2_5jSR5BVRBD8S84vtBdeI834K9-Mj7euLCNit4A/viewform?usp=pp_url). Une clé API vous sera alors fournie pour **Sandbox** et **Production**.

## 3. Authentification

Toutes les requêtes API doivent être authentifiées grâce à notre librairie PHP pour pouvoir accéder aux ressources.

> Pour comprendre le fonctionnement de l'authentification, reportez vous à la [rubrique dédiée](../../Installation-et-utilisation/03-Authentification.md). En particulier, concernant la vérification de la validité de vos tokens.

1. Faites un premier appel à l'API pour vous authentifier via :

```php
$client = Client::create(array(
    'app_id' => 'mon_appid',
    'password' => 'mon_mot_de_passe',
    'live' => false // Permet de choisir l'environnement
  ));
$client->signIn(); // Cet appel fait l'authentification
```

2. Vous recevrez dans le header de la réponse les 4 infos suivantes :

```json
access-token: v763753qcQGBjTn2HcnfUQ
client: R3z_2Ed82uUvApIy4hqtxQ
expiry: 1586424091
uid: 07b8c9c1
```

Ces informations sont spécifiques à votre session et elles sont conservées par notre librairie. Elles vous permettront de rester authentifier lors de vos prochains appels. La session expire à la date fournie par le header `expiry`. [Convertir en ligne le timestamp en date](http://www.timestamp.fr/?)

- Lors de tous vos appels suivants la librairie PHP enverra les 4 infos présentes dans le header de l'authentification.

## 4. Gérer l'expiration d'un token

Si vous obtenez une réponse avec un code `401`, cela signifie que votre token a expiré.

Pour prévenir de ce problème vous pouvez vérifier la validité des tokens :

```php
$authinfo = ["uid" => "e0611906", "access-token" => "thisisnotavalidtoken", "client" => "HLSmEW1TIDqsSMiwuKjnQg", "expiry" => "1590748027"]
$client->validateToken($authinfo);
```

Le `$authinfo` n'est pas un paramètre obligatoire, il permet de tester la validité d'autres paramètres d'authentification.

Si `$authinfo` n'est pas spécifié, ça utilisera ceux du dernier appel de `signIn()`

L'appel renvoie une réponse trouvable dans le `body` avec `"success": boolean` ou bien un code HTTP 200 qui permet de déterminer la validité des informations d'authentification.

Si le token n'est plus valide, il suffit de refaire un `$client->signIn()`

## 5. Eligibilité d'une livraison

<!-- theme: warning -->
> ### Tous nos prix sont en centimes

<!--
type: tab
title: Doc
-->
Pour savoir si la livraison Cocolis est éligible d'un point A à un point B, il faut appeler l'URL :

```php
$client->getRideClient()->canMatch(75000, 31400, 10); // Code postal de départ,Code postal d'arrivé, Volume en m3 de l'objet à transporter
```

---

```json json_schema
{
    "title": "Response",
    "type": "object",
    "properties": {
        "from": {
            "type": "object",
            "properties": {
              "postal_code": {
                "type": "string",
                "description": "Code postal du point de départ"
              }
            },
            "required": [
              "postal_code"
            ]
        },
        "to": {
            "type": "object",
            "properties": {
              "postal_code": {
                "type": "string",
                "description": "Code postal du point d'arrivée"
              }
            },
            "required": [
              "postal_code"
            ]
        },
        "volume": {
            "type": "number",
            "description": "Somme des volumes en m3 des produits à livrer"
        },
        "content_value": {
            "type": "number",
            "description": "Valeur de la livraison en Centimes (Valeur de la commande)"
        }
    },
    "required": [
      "from",
      "to",
      "volume"
    ]
}
```

<!--
type: tab
title: Réponse
-->

```json json_schema
{
    "title": "Response",
    "type": "object",
    "properties": {
        "result": {
            "type": "boolean"
            "description": "Cocolis peut réaliser la livraison"
        },
        "estimated_prices": {
            "type": "object",
            "properties": {
              "with_insurance": {
                "type": "number",
                "description": "Prix avec assurance"
              },
              "regular": {
                "type": "number",
                "description": "Prix sans assurance"
              }
            }
        },
        "insurance_detail": {
          "type": "object",
          "description": "Informations de l'assurance si éligible",
          "properties": {
            "amount": {
              "type": "number",
              "description": "Montant assuré en  !! CENTIMES !!"
            },
            "conditions_url": {
              "type": "number",
              "description": "Liens vers les conditions générales de l'assurance"
            }
          }
        },
        "rider_count": {
            "type": "number",
            "description": "Le nombre de porteur déjà disponibles pour effectuer la livraison"
        }
    }
}
```

<!--
type: tab
title: Essayez-la
-->

```json http
{
  "method": "post",
  "url": "https://api.cocolis.fr/api/v1/rides/can_match",
  headers: {
    Content-Type: application/json,
    token-type: Bearer,
    uid: uid_of_previous_request,
    access-token: access_token_of_previous_request,
    client: client_of_previous_request,
    expiry: expiry_of_previous_request
  },
  body: {
    from: {
      postal_code: 'code_postal_de_depart',
    },
    to: {
      postal_code: 'code_postal_d_arrivee',
    }
  }
}
```

<!-- type: tab-end -->

## 6. Création d'une annonce :

<!-- theme: warning -->
> ### Tous nos prix sont en centimes

Quand une vente a été réalisée sur votre site avec notre mode de livraison, il faut ensuite la créer sur Cocolis. Nous vous recommandons de la créer 30 minutes après le paiement pour gérer des cas d'annulation rapide sur votre site.

<!--
type: tab
title: Doc
-->

```php
$rideClient = $client->getRideClient();
$ride = $rideClient->create($params);
```
---

```json json_schema
{
  "type": "object",
  "description": "Body de la requête",
  "properties": {
    "ride": {
      "$ref": "../models/ride/ride-create.v1.json"
    }
  }
}
```

<!--
type: tab
title: Réponse
-->

```json json_schema
{
  "description": "Réponse",
  "$ref": "../models/ride/ride-full.v1.json"
}
```

<!--
type: tab
title: Essayez-la
-->

```json http
{
  "method": "post",
  "url": "https://api.cocolis.fr/api/v1/rides",
  headers: {
    Content-Type: application/json,
    token-type: Bearer,
    uid: uid_of_previous_request,
    access-token: access_token_of_previous_request,
    client: client_of_previous_request,
    expiry: expiry_of_previous_request
  },
  body: {
    ride: {
      "description": "Carcassonne vers toulu",
      "from_lat": "43.212498",
      "to_lat": "43.599120",
      "from_address": "Carcassonne",
      "to_address": "Toulouse",
      "from_lng": "2.350351",
      "to_lng": "1.444391",
      "from_is_flexible": true,
      "from_pickup_date": "2020-04-13T14:21:21+00:00",
      "to_is_flexible": true,
      "to_pickup_date": "2020-04-13T14:21:21+00:00",
      "is_passenger": false,
      "is_packaged": false,
      "from_availabilities": {
        "weekdays_daytime": true,
        "weekdays_evening": true,
        "weekend_daytime": false,
        "weekend_evening": true
      },
      "to_availabilities": {
        "weekdays_daytime": true,
        "weekdays_evening": true,
        "weekend_daytime": false,
        "weekend_evening": true
      },
      "price": "57000",
      "volume": "15",
      "environment": "objects",
      "from_need_help": "false",
      "from_need_help_floor": "0",
      "from_need_help_elevator": "false",
      "from_need_help_furniture_lift": "false",
      "to_need_help": "false",
      "to_need_help_floor": "0",
      "to_need_help_elevator": "false",
      "to_need_help_furniture_lift": "false",
      "rider_extra_information": "Extra informations",
      "photos": [],
      "ride_objects_attributes": [
        {
          "title": "Canapé",
          "qty": 1,
          "format": "xxl"
        }
      ]
    }
  }
}
```

<!-- type: tab-end -->

## 7. Suivi de la livraison par votre site

A chaque changement de statut de l'annonce sur Cocolis, notre système vous enverra des webhooks [Voir documentation détaillée](../05-Webhooks.md) aux URLs que vous avez spécifiées. De cette manière, vous pourrez suivre les différentes étapes de la livraison.

Dans la documentation détaillée, nous vous donnons un exemple des actions à mettre en place de votre coté selon l'évènement.

## 8. Suivi de la livraison par l'acheteur

L'acheteur dispose d'une interface dédiée pour le suivi de la livraison. Lors de la création de l'annonce, nous vous avons retourné un `buyer_tracking` qui est un code de suivi pour votre acheteur. Il permet de constuire l'URL de suivi qui prend la forme suivante :

```
https://:domain/rides/buyer/:buyer_tracking
```

<!--
type: tab
title: Paramètres
-->


| Paramètre        |      Valeur      |   Commentaire |
| ------------- | :-----------: | -----: |
| :domain      | `www.cocolis.fr` | En sandbox, le domaine sera `sandbox.cocolis.fr` |
| :buyer_tracking      |   ride.buyer_tracking    |   Lors de la création de la ride, nous vous avons renvoyé ce paramètre dans la clé `buyer_tracking` |

> Nous vous conseillons de remonter cette information sur la page de suivi de commande de votre client.

<!--
type: tab
title: Exemples
-->

### Production

```
https://www.cocolis.fr/rides/buyer/CFE9E3620F1626F9
```

### Sandbox

```
https://sandbox.cocolis.fr/rides/buyer/CFE9E3620F1626F9
```

<!-- type: tab-end -->

## 9. Suivi de la livraison par le vendeur

Le veudeur dispose d'une interface dédiée pour suivre la livraison. Lors de la création de l'annonce, nous retournons un `seller_tracking` qui est un code de suivi pour votre vendeur. Il permet de constuire l'URL de suivi qui prend la forme suivante :

```
https://:domain/rides/seller/:seller_tracking
```

```php
$ride = $client->create($params);
$ride->getSellerURL();
```

<!--
type: tab
title: Paramètres
-->

| Paramètre        |      Valeur      |   Commentaire |
| ------------- | :-----------: | -----: |
| :domain      | `www.cocolis.fr` | En sandbox, le domaine sera `sandbox.cocolis.fr` |
| :seller_tracking      |   ride.seller_tracking    |   Lors de la création de la ride, nous vous avons renvoyé ce paramètre dans la clé `seller_tracking` |

> Nous vous conseillons de remonter cette information sur la page de suivi de commande de votre client.

<!--
type: tab
title: Exemples
-->

### Production

```
https://www.cocolis.fr/rides/seller/7E20B021BF8721A2
```

### Sandbox

```
https://sandbox.cocolis.fr/rides/seller/7E20B021BF8721A2
```

<!-- type: tab-end -->


