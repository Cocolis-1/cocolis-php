<?php

/**
 * Cocolis API Class
 * API Documentation: https://doc.cocolis.fr
 * Class Documentation: https://github.com/Cocolis-1/cocolis-php/
 *
 * @author Cocolis
 */

namespace Cocolis\Api;

use Cocolis\Api\Clients\AbstractClient;

class WebhookClient extends AbstractClient
{
  public $_rest_path = 'applications/webhooks';
}
