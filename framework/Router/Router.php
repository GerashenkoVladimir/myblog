<?php

namespace Framework\Router;

use Framework\DI\Service;
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

    public function __construct()
    {
        $this->registry = Registry::getInstance();
        $this->routes   = $this->registry['config']['routes'];
    }

    public function generateURL($routeName)
    {
        if (isset($this->registry['config']['routes'][$routeName]['pattern'])) {
            return "http://".Service::get('request')->getHTTPHost()."{$this->registry['config']['routes'][$routeName]['pattern']}";
        } else {
            return '';
        }

    }

    /**
     * Parses the request URI, determines which need to use the controller and action, returns associative array that
     *                          contains controller, action and arguments.
     *
     * @access public
     * @return array|null
     */
    public function getRoute()
    {
        $uri = Service::get('request')->getUri();

        $this->routes = $this->registry['config']['routes'];

        $matchedRoutes = array();

        foreach ($this->routes as $route) {
            $pattern = $this->preparePattern($route);

            if ($this->compareRoute($pattern, $uri)) {
                array_push($matchedRoutes, $route);
            }
        }

        $args = $this->getArgs($uri);

        if($matchedRoutes == null){
            return null;
        }
        $readyRoute = $this->filterHTTPMethods($matchedRoutes);

        return array('controller' => $readyRoute['controller'],
                         'action' => $readyRoute['action'].'Action',
                           'args' => $args);
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
                    Service::get('request')->getRequestMethod() == $mR['_requirements']['_method']
                ) {

                    $readyRoute = $mR;

                    return $readyRoute;
                }
            }
        }

        return $matchedRoutes[0];
    }
}