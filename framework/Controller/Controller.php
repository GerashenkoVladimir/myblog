<?php

namespace Framework\Controller;

use Framework\Registry\Registry;
use Framework\Renderer\Renderer;
use Framework\Response\Response;

abstract class Controller
{
    protected $registry;

    public function __construct()
    {
        $this->registry = Registry::getInstance();
    }

    protected function render($template, $args)
    {
        $renderer = new Renderer($this->generatePath());
        $keys = array();
        foreach ($args as $arg => $value) {
            $renderer->set($arg, $value);
            $keys[] = $arg;
        }
        return $renderer->generatePage($template, $keys);


    }

    private function generatePath()
    {
        $calledClass = array_pop(explode('\\', get_called_class()));
        $end = strpos($calledClass, 'Controller');
        return $this->registry['config']['layouts'].substr($calledClass, 0, $end).'/';

    }
}