<?php

namespace Framework\Registry;

use Framework\Exception\RegistryExceptions;
use Framework\Request\Request;

class Registry implements \ArrayAccess
{
    private $vars = array();

    private static $_instance;

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    private function __construct()
    {
        $this['request'] = new Request();
    }

    private function set($key, $var)
    {
        if (isset($this->vars[$key])) {
            throw new RegistryExceptions("Variable with key $key already exists!");
        }

        $this->vars[$key] = $var;

        return true;
    }

    private function get($key)
    {
        if (isset($this->vars[$key])) {
            return $this->vars[$key];
        } else {
            return false;
        }
    }

    private function remove($key)
    {
        unset($this->vars[$key]);
    }

    public function offsetExists($offset)
    {
        return isset($this->vars[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}