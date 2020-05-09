<?php

/**
 * Cocolis API Class
 * API Documentation: https://stoplight.io/p/docs/gh/Cocolis-1/cocolis-api
 * Class Documentation: https://github.com/Cocolis-1/cocolis-php/
 *
 * @author Alexandre BETTAN
 */

namespace Cocolis\Api\Client;

class Client{
    private $_app_id;
    private $_password;
    private $_live;

    public function getAppId(){
        return $this->_app_id;
    }

    public function isLive(){
        return $this->_live;
    }

}