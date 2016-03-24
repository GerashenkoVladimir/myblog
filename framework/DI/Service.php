<?php
namespace Framework\DI;

use Framework\Exception\ServiceException;

/**
 * Class Service
 * @package Framework\DI
 */
class Service
{
    /**
     * An associative array that contains class names, objects or closures
     *
     * @access private
     * @static
     *
     * @var array
     */
    private static $definitions = array();

    /**
     * An associative array that contains class names, objects or closures that must be in single copy
     *
     * @access private
     *
     * @var array
     */
    private static $singlDefinitions = array();

    /**
     * An associative array that contains arguments that used in creating services
     *
     * @access private
     * @static
     *
     * @var array
     */
    private static $params = array();

    /**
     * An associative array that contains simple data(array, string or something else)
     *
     * @access private
     * @static
     *
     * @var array
     */
    private static $simpleDefinitions = array();

    /**
     * Sets services
     *
     * @access public
     * @static
     *
     * @param string                 $serviceName Alias
     * @param string|\Closure|object $definition
     * @param array                  $params      Dependencies or arguments
     *
     * @return void
     * @throws ServiceException
     */
    public static function set($serviceName, $definition, array $params = array())
    {
        if (!$definition instanceof \Closure && !is_object($definition) && !is_string($definition)) {
            throw new ServiceException('The parameter specified at registration should be based object,
             closure or class name!');
        }
        if (isset(self::$singlDefinitions[$serviceName])) {
            unset(self::$singlDefinitions[$serviceName]);
        } elseif (isset(self::$simpleDefinitions[$serviceName])) {
            unset(self::$simpleDefinitions[$serviceName]);
        }
        self::$definitions[$serviceName] = $definition;
        self::$params[$serviceName] = $params;
    }

    /**
     * Sets singleton services
     *
     * @access public
     * @static
     *
     * @param string                 $serviceName Alias
     * @param string|\Closure|object $definition
     * @param array                  $params      Dependencies or arguments
     *
     * @return void
     * @throws ServiceException
     */
    public static function setSingleton($serviceName, $definition, array $params = array())
    {
        if (!$definition instanceof \Closure && !is_object($definition) && !is_string($definition)) {
            throw new ServiceException('The parameter specified at registration should be based object,
             closure or class name!');
        }

        if (isset(self::$definitions[$serviceName])) {
            unset(self::$definitions[$serviceName]);
        } elseif (isset(self::$simpleDefinitions[$serviceName])) {
            unset(self::$simpleDefinitions[$serviceName]);
        }
        self::$singlDefinitions[$serviceName] = $definition;
        self::$params[$serviceName] = $params;

    }

    /**
     * Sets simple services
     *
     * @access public
     * @static
     *
     * @param string $serviceName Alias
     * @param mixed  $data        Service
     *
     * @return void
     */
    public static function setSimple($serviceName, $data)
    {
        if (isset(self::$definitions[$serviceName])) {
            unset(self::$definitions[$serviceName]);
        } elseif (isset(self::$singlDefinitions[$serviceName])) {
            unset(self::$simpleDefinitions[$serviceName]);
        }
        self::$simpleDefinitions[$serviceName] = $data;
    }

    /**
     * Returns service
     *
     * @access public
     * @static
     *
     * @param string $serviceName Service name
     * @param array  $params
     *
     * @return mixed|object
     * @throws ServiceException
     */
    public static function get($serviceName, $params = array())
    {
        if (!isset(self::$definitions[$serviceName]) && !isset(self::$singlDefinitions[$serviceName])
            && !isset(self::$simpleDefinitions[$serviceName])
        ) {
            throw new ServiceException("Service '$serviceName' is not registered!");
        }

        if ($isSingleton = isset(self::$singlDefinitions[$serviceName])) {
            $definition = self::$singlDefinitions[$serviceName];
        } elseif (isset(self::$definitions[$serviceName])) {
            $definition = self::$definitions[$serviceName];
        } else {
            return self::$simpleDefinitions[$serviceName];
        }

        $args = self::getArgs($serviceName, $params);

        if ($definition instanceof \Closure) {
            return self::useClosure($definition, $args);
        } elseif (is_object($definition)) {
            return $definition;
        } elseif (is_string($definition) && class_exists($definition)) {
            $classRefObj = new \ReflectionClass($definition);

            if ($isSingleton) {
                return self::$singlDefinitions[$serviceName] = $classRefObj->newInstanceArgs($args);
            }

            return $classRefObj->newInstanceArgs($args);
        }

        throw new ServiceException("Class $definition is not exists!");
    }

    /**
     * Returns the arguments using the alias
     *
     * @access private
     * @static
     *
     * @param string $serviceName Alias
     * @param array  $params      Arguments
     *
     * @return array
     * @throws ServiceException
     */
    private static function getArgs($serviceName, $params)
    {
        if (empty($params)) {

            $args = array();
            foreach (self::$params[$serviceName] as $param) {
                $args[] = self::get($param);
            }
        } else {
            $args = $params;
        }

        return $args;
    }

    /**
     * Uses closure function
     *
     * @access private
     * @static
     *
     * @param \Closure $definition
     * @param array    $params
     *
     * @return mixed
     */
    private static function useClosure($definition, $params = array())
    {
        return call_user_func_array($definition, $params);
    }
}