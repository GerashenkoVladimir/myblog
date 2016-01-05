<?php

namespace Framework\Registry;

use Framework\Exceptions\RegistryExceptions;

class Registry implements \ArrayAccess
{
    private $vars = array();

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
}