<?php

namespace Framework\Router;

class Router
{
    public static function getRoute()
    {
        $uri =  $_SERVER['REQUEST_URI'];

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