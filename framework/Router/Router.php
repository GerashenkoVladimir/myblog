<?php

namespace Framework\Router;

class Router
{
    private static $matchedRoutes = array();

    private static $controller;

    private static $action;

    public static function getRoute()
    {
        $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $routes = require_once(__DIR__ . '/../../app/config/routes.php');
        echo '<pre>';
        foreach ($routes as $route) {


            if ($route['pattern'] == $uri) {
                array_push(self::$matchedRoutes, $route);
            }
        }

        //дописать "разбор" по методам GET & POST
        if (self::$matchedRoutes != null) {
            self::$controller = self::$matchedRoutes[0]['controller'];
            self::$action = self::$matchedRoutes[0]['action'] . 'Action';

            $controllerObj = new self::$controller();
            $controllerObj->{self::$action}();
        } else {
            echo '404';
        }

        echo '</pre>';
    }

    private function replaceParameters($route)
    {
        $routePattern = '';
        $first = strpos($route['pattern'], '{');
        $last = strpos($route['pattern'], '}');
        if ($first !== false && $last !== false) {
            $length = $last - $first + 1;
            print_r(substr($route['pattern'], $first, $length));
            echo '<br>';
        }
    }
}