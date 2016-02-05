<?php

namespace Framework\Router;

use Framework\Exception\RouterException;
use Framework\Registry\Registry;

/**
 * Class Router
 * Parses the request URI, determines which need to use the controller and action and create object of Controller class.
 *
 * @package Framework\Router
 */
class Router
{
    /**
     * Object of Registry class
     *
     * @access private
     * @var Registry
     */
    private $registry;

    private $routes;

    /**
     * An associative array that contains the parameters of route that matches to the request URI.
     *
     * @access private
     * @var array
     */
    private $readyRoute = array();

    /**
     * Controller name
     *
     * @access private
     * @var string
     */
    private $controller;

    /**
     * Controller action
     *
     * @access private
     * @var string
     */
    private $action;

    /**
     * Array of arguments for controller's action
     *
     * @access private
     * @var array
     */
    private $args;

    public function __construct()
    {
        $this->registry = Registry::getInstance();
        $this->routes   = $this->registry['config']['routes'];
    }

    public function generateURL($routeName)
    {
        if (isset($this->registry['config']['routes'][$routeName]['pattern'])) {
            return "http://{$this->registry['request']->getHTTPHost()}{$this->registry['config']['routes'][$routeName]['pattern']}";
        } else {
            return '';
        }

    }

    /**
     * Parses the request URI, determines which need to use the controller and action and create object
     *                          of Controller class.
     *
     * @access public
     * @throws RouterException
     * @return void
     */
    public function getRoute()
    {
        $uri = $this->registry['request']->getUri();

        $this->routes = $this->registry['config']['routes'];

        $matchedRoutes = array();

        foreach ($this->routes as $route) {
            $pattern = $this->preparePattern($route);

            if ($this->compareRoute($pattern, $uri)) {
                array_push($matchedRoutes, $route);
            }
        }

        $this->args = $this->getArgs($uri);

        try{
            if ($matchedRoutes == null) {
                throw new RouterException("ERROR 404!!! Page not found!");
            }
            $this->readyRoute = $this->filterHTTPMethods($matchedRoutes);
            $this->controller = $this->readyRoute['controller'];
            $this->action     = $this->readyRoute['action'].'Action';
            $this->createController($this->controller, $this->action, $this->args);
        } catch (RouterException $e){
            //Дописать страницу вывода ошибки
            echo $e;
        }
    }

    /**
     * Parses pattern from route array and converts it to a regular expression
     *
     * @param string $route
     *
     * @return string Regular expression
     */
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

    /**
     * Compare pattern (regular expression) with request URI.
     *
     * @param string $pattern Regular expression
     * @param string $uri     Request URI
     *
     * @return bool Returns true if pattern matches with request URI, else - returns false
     */
    private function compareRoute($pattern, $uri)
    {
        if (preg_match("|^".$pattern.'$|', $uri)) {
            return true;
        }

        return false;
    }

    /**
     * Generates arguments from request URI.
     *
     * @access private
     *
     * @param string $uri
     *
     * @return array
     */
    private function getArgs($uri)
    {
        $args = explode('/', $uri);
        array_shift($args);
        array_shift($args);

        return $args;
    }

    /**
     * Filter routes that match request method.
     *
     * @access private
     *
     * @param array $matchedRoutes
     *
     * @return array
     */
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

    /**
     * Creates object of controller and launches action.
     *
     * @access private
     *
     * @param string $controllerName
     * @param string $action
     * @param array  $args
     *
     * @throws RouterException
     * @return void
     */
    private function createController($controllerName, $action, $args)
    {
        if (!method_exists($controllerName, $action)) {
            throw new RouterException("Class \"$controllerName\" or action \"$action\" not exists!");
        }
        $controllerObj = new $controllerName();
        call_user_func_array(array($controllerObj, $action), $args);
    }
}