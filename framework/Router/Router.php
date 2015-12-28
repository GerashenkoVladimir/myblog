<?php

namespace Framework\Router;

class Router
{
    private static $controller;

    private static  $action;

    public static function getRoute()
    {
        $uri =  urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $routes = require_once(__DIR__.'/../../app/config/routes.php');
        echo '<pre>';
        foreach ($routes as $route) {
            if ($route['pattern']==$uri) {
                echo 'Pattern: '.$uri;
            }
        }
        echo '</pre>';
    }
}