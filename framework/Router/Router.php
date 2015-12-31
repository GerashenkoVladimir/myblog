<?php

namespace Framework\Router;

class Router
{
    private static $matchedRoutes = array();

    private static $controller;

    private static $action;

    private static $args;

    public static function getRoute()
    {
        $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $routes = require_once(__DIR__.'/../../app/config/routes.php');

        foreach ($routes as $route) {
            $pattern = $route['pattern'];
            if (preg_match_all('|{(\w+)}|', $route['pattern'], $matches) != null) {
                foreach ($matches[1] as $match => $m) {
                    $pattern = preg_replace('|'.$matches[0][$match].'|', '('.$route['_requirements'][$m].')', $pattern);
                }
            }

            if (preg_match("|^".$pattern.'$|', $uri)) {
                array_push(self::$matchedRoutes, $route);
                $args = explode('/', $uri);
                array_shift($args);
                array_shift($args);
                self::$args = $args;
                if (count(self::$matchedRoutes) > 0) {
                    foreach (self::$matchedRoutes as $mR) {
                        if (isset($mR['_requirements']['_method']) && $_SERVER['REQUEST_METHOD'] == $mR['_requirements']['_method']) {
                            self::$matchedRoutes[0] = $mR;
                        }
                    }
                }
            }
        }

        if (self::$matchedRoutes != null) {
            self::$controller = self::$matchedRoutes[0]['controller'];
            self::$action     = self::$matchedRoutes[0]['action'].'Action';
            call_user_func_array(array(new self::$controller, self::$action), self::$args);
        } else {
            echo '404';
        }
    }
}