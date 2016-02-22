<?php
namespace Framework\DI;

use Framework\Exception\ServiceException;

class Service
{
    private static $definitions = array();
    private static $params = array();

    public static function set($class, $definition , array $params = array())
    {
        if (!$definition instanceof \Closure && !is_object($definition) && !is_string($definition)){
            throw new ServiceException('The parameter specified at registration should be based object,
             closure or class name!');
        }
        self::$definitions[$class] = $definition;
        self::$params[$class] = $params;
    }

    public static function get($class, $params = array())
    {
        if (!isset(self::$definitions[$class])) {
            throw new ServiceException("Service $class is not registered!");
        }

        $definition = self::$definitions[$class];

        if ($definition instanceof \Closure) {
            return self::useClosure($definition, $params);
        } elseif (is_object($definition)) {
            return $definition;
        } elseif (is_string($definition) && class_exists($definition)) {
            if (empty($params)) {
                $args = array();
                foreach (self::$params[$class] as $param) {
                    $args[] = self::get($param);
                }
            } else {
                $args = $params;
            }
            $classRefObj = new \ReflectionClass($definition);

            return $classRefObj->newInstanceArgs($args);
        }
        throw new ServiceException("Class $definition is not exists!");
    }

    private function useClosure($definition, $params = array())
    {
        return call_user_func_array($definition,$params);
    }
}
