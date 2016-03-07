<?php
namespace Framework\DI;

use Framework\Exception\ServiceException;

class Service
{
    private static $definitions = array();
    private static $singlDefinitions = array();
    private static $params = array();
    private static $simpleDefinitions = array();

    public static function set($class, $definition , array $params = array())
    {
        if (!$definition instanceof \Closure && !is_object($definition) && !is_string($definition)){
            throw new ServiceException('The parameter specified at registration should be based object,
             closure or class name!');
        }
        if (isset(self::$singlDefinitions[$class])) {
            unset(self::$singlDefinitions[$class]);
        } elseif (isset(self::$simpleDefinitions[$class])) {
            unset(self::$simpleDefinitions[$class]);
        }
        self::$definitions[$class] = $definition;
        self::$params[$class] = $params;
    }

    public static function setSingleton($class, $definition , array $params = array())
    {
        if (!$definition instanceof \Closure && !is_object($definition) && !is_string($definition)){
            throw new ServiceException('The parameter specified at registration should be based object,
             closure or class name!');
        }
        if (isset(self::$definitions[$class])) {
            unset(self::$definitions[$class]);
        } elseif (isset(self::$simpleDefinitions[$class])) {
            unset(self::$simpleDefinitions[$class]);
        }
        self::$singlDefinitions[$class] = $definition;
        self::$params[$class] = $params;

    }

    public static function setSimple($serviceName, $data)
    {
        if (isset(self::$definitions[$serviceName])) {
            unset(self::$definitions[$serviceName]);
        } elseif (isset(self::$singlDefinitions[$serviceName])) {
            unset(self::$simpleDefinitions[$serviceName]);
        }
        self::$simpleDefinitions[$serviceName] = $data;
    }

    public static function get($class, $params = array())
    {
       if (!isset(self::$definitions[$class]) && !isset(self::$singlDefinitions[$class])
           && !isset(self::$simpleDefinitions[$class])) {
            throw new ServiceException("Service '$class' is not registered!");
        }

        if ($isSingleton = isset(self::$singlDefinitions[$class])) {
            $definition = self::$singlDefinitions[$class];
        } elseif (isset(self::$definitions[$class])) {
            $definition = self::$definitions[$class];
        } else {
            return self::$simpleDefinitions[$class];
        }

        $args = self::getArgs($class,$params);

        if ($definition instanceof \Closure) {
            return self::useClosure($definition, $args);
        } elseif (is_object($definition)) {
            return $definition;
        } elseif (is_string($definition) && class_exists($definition)) {
            $classRefObj = new \ReflectionClass($definition);

            if ($isSingleton) {
                return self::$singlDefinitions[$class] = $classRefObj->newInstanceArgs($args);
            }

            return $classRefObj->newInstanceArgs($args);
        }

        throw new ServiceException("Class $definition is not exists!");
    }

    private static function getArgs($class, $params)
    {
        if (empty($params)) {

            $args = array();
            foreach (self::$params[$class] as $param) {
                $args[] = self::get($param);
            }
        } else {
            $args = $params;
        }

        return $args;
    }

    private function useClosure($definition, $params = array())
    {
        return call_user_func_array($definition,$params);
    }
}