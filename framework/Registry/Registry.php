<?php

namespace Framework\Registry;

use Framework\Exception\RegistryExceptions;
use Framework\Request\Request;

/**
 * Class Registry
 * Contains objects and variables. Implements ArrayAccess interface.
 *
 * @package Framework\Registry
 */
class Registry implements \ArrayAccess
{
    /**
     * An associative array that contains objects and variables.
     *
     * @access private
     * @var array Contains objects or variables $key => $var
     */
    private $vars = array();

    /**
     * Object variable of the Registry class
     *
     * @access private
     * @var Registry
     */
    private static $_instance;

    /**
     * Implements pattern Singlton. Create and return object variable of Registry class.
     *
     * @access public
     * @return Registry
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Add data to the registry.
     *
     * @access private
     *
     * @param string $key Key of param
     * @param mixed  $var Param
     *
     * @return bool Returns true if the value is set.
     * @throws RegistryExceptions
     */
    private function set($key, $var)
    {
        if (isset($this->vars[$key])) {
            throw new RegistryExceptions("Variable with key $key already exists!");
        }

        $this->vars[$key] = $var;

        return true;
    }

    /**
     * Returns element from registry.
     *
     * @acces private
     *
     * @param string $key Key of element.
     *
     * @return bool Returns false if the element does not exists.
     */
    private function get($key)
    {
        if (isset($this->vars[$key])) {
            return $this->vars[$key];
        } else {
            return false;
        }
    }

    /**
     * Remove param form registry.
     *
     * @access private
     *
     * @param string $key Key of param.
     *
     * @return void
     */
    private function remove($key)
    {
        unset($this->vars[$key]);
    }

    /**
     * Implements ArrayAccess interface. Checks element is set.
     *
     * @access public
     *
     * @param mixed $offset Key of element.
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->vars[$offset]);
    }

    /**
     * Implements ArrayAccess interface. Returns element.
     *
     * @access public
     *
     * @param string $offset Key of element.
     *
     * @return mixed|bool
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Implements ArrayAccess interface. Set element.
     *
     * @access public
     *
     * @param string $offset Key of element.
     * @param mixed  $value  Element.
     *
     * @return void
     * @throws RegistryExceptions
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Implements ArrayAccess interface. Remove element.
     *
     * @access public
     *
     * @param mixed $offset Key of element.
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     *
     */
    private function __clone()
    {
    }

    /**
     *
     */
    private function __wakeup()
    {
    }
}