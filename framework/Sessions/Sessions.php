<?php

namespace Framework\Sessions;

use Framework\Inheritance\Singleton;

/**
 * Class Sessions
 * @package Framework\Sessions
 */
class Sessions extends Singleton
{
    /**
     * Previous URL
     *
     * @access public
     *
     * @var string
     */
    public $returnUrl;

    /**
     * Sessions constructor
     *
     * @access protected
     */
    protected function __construct()
    {
        session_start();
    }

    /**
     * Returns data from $_SESSION
     *
     * @access public
     *
     * @param null|string $key
     *
     * @return mixed
     */
    public function get($key = null)
    {
        if ($key == null) {
            return $_SESSION;
        } elseif (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return null;
    }

    /**
     * Sets data to $_SESSION
     *
     * @access public
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Unset data
     *
     * @access public
     *
     * @param string|null $key
     *
     * @return void
     */
    public function unsetParam($key = null)
    {
        if ($key == null) {
            session_unset();
        } elseif (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Check is data exists in $_SESSION
     *
     * @access public
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($_SESSION[$key]) ? true : false;
    }

    /**
     * Destroy session
     *
     * @access public
     *
     * @return void
     */
    public function destroy()
    {
        session_unset();
        session_destroy();
    }
}