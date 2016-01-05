<?php


namespace Framework;

use Framework\Router\Router;

class Application
{
    public function run()
    {
        $router = new Router();
        $router->getRoute();
    }
}