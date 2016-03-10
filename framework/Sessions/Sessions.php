<?php

namespace Framework\Sessions;

use Framework\Inheritance\Singleton;

class Sessions extends Singleton
{
    public $returnUrl;

    protected function __construct()
    {
        session_start();
    }

    public function get($key = null)
    {
        if ($key == null) {
            return $_SESSION;
        } elseif (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return null;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function unsetParam($key = null)
    {
        if ($key == null) {
            session_unset();
        } elseif (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public function has($key)
    {
        return isset($_SESSION[$key])?true:false;
    }

    public function destroy()
    {
        session_unset();
        session_destroy();
    }
}