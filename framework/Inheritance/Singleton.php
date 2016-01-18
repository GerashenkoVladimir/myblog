<?php

namespace Framework\Inheritance;

/**
 * Abstract class Singleton
 * Implements pattern Singleton. Create and return object variable
 *
 * @package Framework\Inheritance
 */
abstract class Singleton
{
    /**
     * Array of object variables
     *
     * @access private
     * @static
     * @var array
     */
    protected static $_instance;

    /**
     * Implements pattern Singleton. Create and return object variable
     *
     * @access public
     * @static
     * @return
     */
    final public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(static::$_instance[$class])) {
            static::$_instance[$class] = new $class();
        }

        return static::$_instance[$class];
    }


    abstract protected function __construct();

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}