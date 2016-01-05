<?php

namespace Framework\Router;

use Framework\Exceptions\RouterExceptions;

class Router
{
    private static $readyRoute = array();

    private static $controller;

    private static $action;

    private static $args;

    public static function getRoute()
    {
        $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $routes = require_once(__DIR__.'/../../app/config/routes.php');

        $matchedRoutes = array();

        foreach ($routes as $route) {
            $pattern = self::preparePattern($route);

            if (self::compareRoute($pattern, $uri)) {
                array_push($matchedRoutes, $route);
            }
        }

        self::$args = self::getArgs($uri);

        try{
            if ($matchedRoutes == null) {
                throw new RouterExceptions("ERROR 404!!! Page not found!");
            }
            self::$readyRoute = self::filterHTTPMethods($matchedRoutes);
            self::$controller = self::$readyRoute['controller'];
            self::$action     = self::$readyRoute['action'].'Action';
            self::createController(self::$controller, self::$action, self::$args);
        } catch (RouterExceptions $e){
            //Дописать страницу вывода ошибки
            echo $e;
        }
    }

    private static function preparePattern($route)
    {
        $pattern = $route['pattern'];
        if (preg_match_all('|{(\w+)}|', $route['pattern'], $matches) != null) {
            foreach ($matches[1] as $match => $m) {
                $pattern = preg_replace('|'.$matches[0][$match].'|', '('.$route['_requirements'][$m].')', $pattern);
            }
        }

        return $pattern;
    }

    private static function compareRoute($pattern, $uri)
    {
        if (preg_match("|^".$pattern.'$|', $uri)) {
            return true;
        }

        return false;
    }

    private static function getArgs($uri)
    {
        $args = explode('/', $uri);
        array_shift($args);
        array_shift($args);

        return $args;
    }

    private static function filterHTTPMethods($matchedRoutes)
    {
        if (count($matchedRoutes) > 1) {
            foreach ($matchedRoutes as $mR) {
                if (isset($mR['_requirements']['_method']) && $_SERVER['REQUEST_METHOD'] == $mR['_requirements']['_method']) {
                    $readyRoute = $mR;

                    return $readyRoute;
                }
            }
        }

        return $matchedRoutes[0];
    }

    private static function createController($controllerName, $action, $args)
    {
        if (!method_exists($controllerName, $action)) {
            throw new RouterExceptions("Class \"".$controllerName."\" or action \"".$action."\" not exists!");
        }
        call_user_func_array(array($controllerName, $action), $args);
    }
}