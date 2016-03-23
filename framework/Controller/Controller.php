<?php

namespace Framework\Controller;

use Framework\DI\Service;
use Framework\Renderer\Renderer;
use Framework\Request\Request;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;

/**
 * Class Controller
 *
 * @package Framework\Controller
 * @abstract
 */
abstract class Controller
{

    /**
     * Redirects to the specified route
     *
     * @access public
     *
     * @param string     $route
     * @param null|array $message
     *
     * @return ResponseRedirect
     * @throws \Framework\Exception\ServiceException
     */
    public function redirect($route, $message = null)
    {
        if ($message != null) {
            Service::get('flushMessenger')->setMessage($message);
        }

        return new ResponseRedirect($route);
    }

    /**
     * Content rendering method
     *
     * @access protected
     *
     * @param string $template
     * @param array  $args
     *
     * @return Response
     */
    protected function render($template, $args = array())
    {

        $renderer = new Renderer($this->generatePath());
        $keys = array();
        foreach ($args as $arg => $value) {
            $renderer->set($arg, $value);
            $keys[] = $arg;
        }

        return new Response($renderer->generatePage($template, $keys));
    }

    /**
     * Generates full path to controller
     *
     * @access private
     *
     * @return string
     * @throws \Framework\Exception\ServiceException
     */
    private function generatePath()
    {
        $classNameArray = explode('\\', get_called_class());
        $calledClass = array_pop($classNameArray);
        $end = strpos($calledClass, 'Controller');
        return Service::get('config')['layouts'] . substr($calledClass, 0, $end) . '/';

    }

    /**
     * Generates full route
     *
     * @access protected
     *
     * @param string $route
     *
     * @return string
     * @throws \Framework\Exception\ServiceException
     */
    protected function generateRoute($route)
    {
        return Service::get('router')->generateURL($route);
    }

    /**
     * Returns object of Request class
     *
     * @access public
     *
     * @return Request
     * @throws \Framework\Exception\ServiceException
     */
    public function getRequest()
    {
        return Service::get('request');
    }
}