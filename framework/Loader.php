<?php


/**
 * Class Loader
 * Automatically loads used classes.
 */
class Loader
{
    /**
     * An associative array that contains the namespaces and file paths.
     *
     * @access private
     * @var array Contains information about the $namespace => $path
     */
    private static $classesMap = array();

    /**
     * Add custom namespace and file paths
     *
     * @access public
     *
     * @param string  $namespace
     * @param  string $path
     *
     * @return bool Returns false if $path is not directory.
     */
    public static function addNamespacePath($namespace, $path)
    {
        if (is_dir($path)) {
            self::$classesMap[$namespace] = $path;
        } else {
            return false;
        }
    }

    /**
     * Automatically loads used classes.
     *
     * @access public
     *
     * @param string $classname
     *
     * @return bool
     */
    public static function autoload($classname)
    {
        $parts = explode('\\', $classname);

        foreach (self::$classesMap as $namespace => $dirPath) {
            if ($namespace == $parts[0].'\\') {
                array_shift($parts);
                $filePath = $dirPath.'/'.implode('/', $parts).'.php';
                if (file_exists($filePath)) {
                    require_once $filePath;

                    return true;
                } else {
                    return false;
                }
            }
        }

        $filePath = __DIR__.'/../'.lcfirst($classname).'.php';
        if (file_exists($filePath)) {
            require_once $filePath;

            return true;
        } else {
            return false;
        }
    }

}

spl_autoload_register(array('Loader', 'autoload'));
