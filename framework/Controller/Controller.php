<?php

namespace Framework\Controller;

use Framework\DI\Service;
use Framework\Renderer\Renderer;
use Framework\Request\Request;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;

abstract class Controller
{


    public function __construct()
    {
    }

    public function redirect($route, $message = null)
    {
        if ($message != null) {
            Service::get('flushMessenger')->setMessage($message);
        }

        return new ResponseRedirect($route);
    }

    protected function render($template, $args)
    {

        $renderer = new Renderer($this->generatePath());
        $keys = array();
        foreach ($args as $arg => $value) {
            $renderer->set($arg, $value);
            $keys[] = $arg;
        }

        return new Response($renderer->generatePage($template, $keys));
    }

    private function generatePath()
    {
        $calledClass = array_pop(explode('\\', get_called_class()));
        $end = strpos($calledClass, 'Controller');
        return Service::get('config')['layouts'].substr($calledClass, 0, $end).'/';

    }

    protected function generateRoute($route)
    {
        return Service::get('router')->generateURL($route);
    }

    /**
     * @return Request
     * @throws \Framework\Exception\ServiceException
     */
    public function getRequest()
    {
        return Service::get('request');
    }
}