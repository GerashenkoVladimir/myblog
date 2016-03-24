<?php

namespace Framework\Inheritance;

/**
 * Class Singleton
 * Implements pattern Singleton. Create and return object variable
 *
 * @package Framework\Inheritance
 * @abstract
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
    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(static::$_instance[$class])) {
            static::$_instance[$class] = new $class();
        }

        return static::$_instance[$class];
    }

    /**
     * Singleton constructor
     *
     * @access protected
     */
    protected function __construct(){

    }

    /**
     * @final
     * @access protected
     */
    final protected function __clone()
    {
    }

    /**
     * @final
     * @access protected
     */
    final protected function __wakeup()
    {
    }
}