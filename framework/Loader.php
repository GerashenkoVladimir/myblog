<?php

class Loader
{
    private static $classesMap = array();

    public static function addNamespacePath($namespace, $path)
    {
        if (is_dir($path)) {
            self::$classesMap[$namespace] = $path;

        } else {
            return false;
        }
    }

    public static function autoload($classname)
    {
        $path = __DIR__.'/../'.lcfirst($classname).'.php';
        if (file_exists($path)) {
            require_once $path;
        }

    }

}

spl_autoload_register(array('Loader','autoload'));


