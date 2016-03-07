<?php

namespace Framework\Request;

use Framework\DI\Service;

/**
 * Class Request
 * Implements work with request.
 *
 * @package Framework\Request
 */
class Request
{
    /**
     * An associative array that contains the headers of http request.
     *
     * @access private
     * @var array
     */
    private $allHeaders = array();

    /**
     * Request URI.
     *
     * @access private
     * @var string
     */
    private $uri;

    /**
     * Method of http request.
     *
     * @access private
     * @var string
     */
    private $requestMethod;

    /**
     * Server protocol.
     *
     * @access private
     * @var string
     */
    private $serverProtocol;

    /**
     * HTTP host
     *
     * @access private
     * @var string
     */
    private $httpHost;

    /**
     * An associative array that contains the $_GET array.
     *
     * @access private
     * @var array
     */
    private $getData = array();

    /**
     * An associative array that contains the $_POST array.
     *
     * @access private
     * @var array
     */
    private $postData = array();

    private $cookieData = array();

    public function __construct()
    {
        $this->allHeaders     = getallheaders();
        $this->uri            = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->requestMethod  = $_SERVER['REQUEST_METHOD'];
        $this->serverProtocol = $_SERVER['SERVER_PROTOCOL'];
        $this->httpHost       = $_SERVER['HTTP_HOST'];
        $this->getData  = $this->sanitizeData($_GET);
        $this->postData = $this->sanitizeData($_POST);
        $this->cookieData = $this->sanitizeData($_COOKIE);
    }

    /**
     * Returns all headers of http request.
     *
     * @access public
     * @return array
     */
    public function getAllHeaders()
    {
        return $this->allHeaders;
    }

    /**
     * Returns header of http request.
     *
     * @access public
     * @param string $key
     * @return string|null
     */
    public function header($key)
    {
        return isset($this->allHeaders[$key]) ? $this->allHeaders[$key] : null;
    }

    /**
     * Returns method of http request.
     *
     * @access public
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * Return "true" if request method is POST
     *
     * @access public
     * @return boolean
     */
    public function isPost()
    {
        if ($this->requestMethod == 'POST') {
            return true;
        }
        return false;
    }

    /**
     * Returns server protocol.
     *
     * @access public
     * @return string
     */
    public function getServerProtocol()
    {
        return $this->serverProtocol;
    }

    /**
     * Returns HTTP host
     *
     * @access public
     * @return string
     */
    public function getHTTPHost()
    {
        return $this->httpHost;
    }

    /**
     * Returns all data of $_GET array.
     *
     * @access public
     * @return array
     */
    public function getAllGet()
    {
        return $this->getData;
    }

    /**
     * Returns element of $_GET array.
     *
     * $access public
     * @param string $key
     * @return string|null
     */
    public function get($key)
    {
        return isset($this->getData[$key]) ? $this->getData[$key] : null;
    }

    /**
     * Returns data of $_POST array.
     *
     * @access public
     * @return array
     */
    public function getAllPost()
    {
        return $this->postData;
    }

    /**
     * Returns element of $_POST array.
     *
     * @access public
     * @param string $key
     * @return string|null
     */
    public function post($key)
    {
        return isset($this->postData[$key]) ? $this->postData[$key] : null;
    }

    /**
     * Returns request URI.
     *
     * @access public
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    public function checkToken()
    {
        if ($this->post('token') === Service::get('session')->get('token')) {

            return true;
        }

        return false;
    }

    private function sanitizeData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $value = $this->sanitizeString($value);
            }
        } else {
            $data = $this->sanitizeString($data);
        }
        return $data;
    }

    private function sanitizeString($data)
    {
        $data = stripslashes($data);
        $data = htmlentities($data);
        $data = strip_tags($data);
        return $data;
    }
}