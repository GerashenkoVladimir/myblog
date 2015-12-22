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
        self::$classesMap = require_once(__DIR__.'/../app/config/classesmap.php');
        foreach (self::$classesMap as $namespace => $path) {
            if ($namespace == $classname) {
                require_once $path;
                break;
            }
        }
    }

}

spl_autoload_register(array('Loader','autoload'));


