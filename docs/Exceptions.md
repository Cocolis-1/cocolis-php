---
tags: [exceptions, errors, 200, 400, status]
---

# Exceptions

Certaines requêtes vers l'API de Cocolis peuvent mener à des erreurs parfois indétectables si elles ne sont pas traîtées correctement par votre application.

### Détection des exceptions

Lorsque les requêtes vers l'API de Cocolis renvoie des codes d'erreurs supérieurs à 400, la librairie PHP génère des **Exceptions**.

### Types d'exception

#### \Cocolis\Api\Curl\UnauthorizedException

Les exceptions **UnauthorizedException** surviennent lorsque l'authentification échoue sur l'API de Cocolis.

Voici un exemple de code :

```php
try {
    echo "Aucune erreur est survenue";
} catch (\Cocolis\Api\Curl\UnauthorizedException::class $e) {
    echo "Erreur d'authentification survenue";
}
```

#### \Cocolis\Api\Curl\NotFoundException

Les exceptions **NotFoundException** surviennent lorsque la route demandée est introuvable sur l'API de Cocolis.

Un exemple de code dans une situation similaire que pour l'exception **UnauthorizedException** :

``` php
try {
    echo "Aucune erreur est survenue";
} catch (\Cocolis\Api\Curl\NotFoundException::class $e) {
    echo "Erreur 404";
}
```

#### \Cocolis\Api\Curl\InternalErrorException

Enfin, les erreurs internes renvoyées par l'API de Cocolis au-delà du code d'erreur 500 génèrent des exceptions du type : **InternalErrorException**.

Un exemple de code dans une situation similaire que pour l'exception **NotFoundException** :

``` php
try {
    echo "Aucune erreur est survenue";
} catch (\Cocolis\Api\Curl\InternalErrorException::class $e) {
    echo "Erreur interne à l'API";
}
```

