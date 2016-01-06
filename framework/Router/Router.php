<?php

namespace Framework\Router;

use Framework\Exception\RouterExceptions;
use Framework\Registry\Registry;

class Router
{
    private $registry;

    private $readyRoute = array();

    private $controller;

    private $action;

    private $args;

    public function __construct()
    {
        $this->registry = Registry::getInstance();
    }

    public function getRoute()
    {
        $uri = $this->registry['request']->getUri();

        $routes = require_once(__DIR__.'/../../app/config/routes.php');

        $matchedRoutes = array();

        foreach ($routes as $route) {
            $pattern = $this->preparePattern($route);

            if ($this->compareRoute($pattern, $uri)) {
                array_push($matchedRoutes, $route);
            }
        }

        $this->args = $this->getArgs($uri);

        try{
            if ($matchedRoutes == null) {
                throw new RouterExceptions("ERROR 404!!! Page not found!");
            }
            $this->readyRoute = $this->filterHTTPMethods($matchedRoutes);
            $this->controller = $this->readyRoute['controller'];
            $this->action     = $this->readyRoute['action'].'Action';
            $this->createController($this->controller, $this->action, $this->args);
        } catch (RouterExceptions $e){
            //Дописать страницу вывода ошибки
            echo $e;
        }
    }

    private function preparePattern($route)
    {
        $pattern = $route['pattern'];
        if (preg_match_all('|{(\w+)}|', $route['pattern'], $matches) != null) {
            foreach ($matches[1] as $match => $m) {
                $pattern = preg_replace('|'.$matches[0][$match].'|', '('.$route['_requirements'][$m].')', $pattern);
            }
        }

        return $pattern;
    }

    private function compareRoute($pattern, $uri)
    {
        if (preg_match("|^".$pattern.'$|', $uri)) {
            return true;
        }

        return false;
    }

    private function getArgs($uri)
    {
        $args = explode('/', $uri);
        array_shift($args);
        array_shift($args);

        return $args;
    }

    private function filterHTTPMethods($matchedRoutes)
    {
        if (count($matchedRoutes) > 1) {
            foreach ($matchedRoutes as $mR) {
                if (isset($mR['_requirements']['_method']) &&
                    $this->registry['request']->getRequestMethod() == $mR['_requirements']['_method']
                ) {

                    $readyRoute = $mR;

                    return $readyRoute;
                }
            }
        }

        return $matchedRoutes[0];
    }

    private function createController($controllerName, $action, $args)
    {
        if (!method_exists($controllerName, $action)) {
            throw new RouterExceptions("Class \"$controllerName\" or action \"$action\" not exists!");
        }
        $controllerObj = new $controllerName($this->registry);
        call_user_func_array(array($controllerObj, $action), $args);
    }
}