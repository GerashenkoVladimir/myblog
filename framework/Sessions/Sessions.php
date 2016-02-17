<?php

namespace Framework\Sessions;

use Framework\Inheritance\Singleton;

class Sessions extends Singleton
{

    protected function __construct()
    {

    }

    private static $sessionStarted = false;

    private static function start()
    {
        if (!self::$sessionStarted) {
            session_start();
            self::$sessionStarted = true;
        }
    }

    public static function getAllSessionData()
    {
        self::start();
        return $_SESSION;
    }

    public static function get($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function unsetParam($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function unsetAll()
    {
        self::start();
        session_unset();
    }

    public static function destroy()
    {
        self::start();
        session_unset();
        session_destroy();
    }


}