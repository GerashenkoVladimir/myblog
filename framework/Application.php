<?php

namespace Framework;

use Framework\Registry\Registry;
use Framework\Request\Request;
use Framework\Router\Router;

class Application
{
    public function run()
    {
        $registry            = Registry::getInstance();
        $registry['request'] = new Request();
        $router              = new Router();
        $router->getRoute();
    }
}