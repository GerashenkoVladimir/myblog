<?php

namespace Framework\Controller;

use Framework\DI\Service;
use Framework\Registry\Registry;
use Framework\Renderer\Renderer;
use Framework\Response\Response;
use Framework\Response\ResponseRedirect;

abstract class Controller
{

    protected $registry;

    public function __construct()
    {
        $this->registry = Registry::getInstance();
    }

    public function redirect($route)
    {
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
        return $this->registry['config']['layouts'].substr($calledClass, 0, $end).'/';

    }

    protected function generateRoute($route)
    {
        return Service::get('router')->generateURL($route);
    }

    public function getRequest()
    {
        return Service::get('request');
    }
}