<?php

namespace Framework\DI;

class Service
{
    private static $resolvers;

    public static function get($key)
    {
        self::initResolvers();
        $resolve = self::$resolvers[$key];
        return $resolve();
    }

    public static function set($key, $value)
    {
        self::$resolvers[$key] = $value;
    }

    private static function initResolvers()
    {
        if(is_null(self::$resolvers)){
            self::$resolvers = require_once 'resolvers.php';
        }
    }
}