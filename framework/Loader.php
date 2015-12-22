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
        $parts = explode('\\', $classname);

        foreach (self::$classesMap as $namespace => $dirPath) {
            if ($namespace == $parts[0] . '\\') {
                array_shift($parts);
                $filePath = $dirPath . '/' . implode('/', $parts) . '.php';
                if (file_exists($filePath)) {
                    require_once $filePath;
                    return true;
                } else {
                    return false;
                }
            }
        }

        $filePath = __DIR__ . '/../' . lcfirst($classname) . '.php';
        if (file_exists($filePath)) {
            require_once $filePath;
            return true;
        } else {
            return false;
        }

    }

}

spl_autoload_register(array('Loader','autoload'));
