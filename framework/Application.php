<?php

namespace Framework;

use Framework\DataBase\DataBase;
use Framework\Registry\Registry;
use Framework\Request\Request;
use Framework\Router\Router;

class Application
{
    public function run()
    {
        $registry            = Registry::getInstance();
        $registry['request'] = new Request();
        $registry['config']  = require_once('../app/config/config.php');

        $router              = new Router();
        $router->getRoute();

        echo '<pre>';
        DataBase::getInstance();
        echo '</pre>';
    }
}