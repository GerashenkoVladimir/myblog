<?php

namespace Framework\Request;

use Framework\Registry\Registry;

/**
 * Class Request
 * Implements work with request.
 * @package Framework\Request
 */
class Request
{
    /**
     * Object of Registry class
     * @access private
     * @var Registry
     */
    private $registry;

    /**
     * An associative array that contains the headers of http request.
     * @access private
     * @var array
     */
    private $allHeaders = array();

    /**
     * Request URI.
     * @access private
     * @var string
     */
    private $uri;

    /**
     * Method of http request.
     * @access private
     * @var string
     */
    private $requestMethod;

    /**
     * Server protocol.
     * @access private
     * @var string
     */
    private $serverProtocol;

    /**
     * An associative array that contains the $_GET array.
     * @access private
     * @var array
     */
    private $getData = array();

    /**
     * An associative array that contains the $_POST array.
     * @access private
     * @var array
     */
    private $postData = array();

    /**
     * An associative array that contains the $_SESSION array.
     * @access private
     * @var array
     */
    private $sessionData = array();

    public function __construct()
    {
        $this->registry       = Registry::getInstance();
        $this->allHeaders     = getallheaders();
        session_start();
        $this->uri            = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->requestMethod  = $_SERVER['REQUEST_METHOD'];
        $this->serverProtocol = $_SERVER['SERVER_PROTOCOL'];
        $this->sessionData    = $_SESSION;
        //добавить валидацию
        $this->getData        = $_GET;
        $this->postData       = $_POST;
    }

    /**
     * Returns headers of http request.
     * @access public
     * @return array
     */
    public function getHeaders()
    {
        return $this->allHeaders;
    }

    /**
     * Returns method of http request.
     * @access public
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * Returns server protocol.
     * @access public
     * @return string
     */
    public function getServerProtocol()
    {
        return $this->serverProtocol;
    }

    /**
     * Returns data of $_GET array.
     * @access public
     * @return array
     */
    public function getGetData()
    {
        return $this->getData;
    }

    /**
     * Returns data of $_POST array.
     * @access public
     * @return array
     */
    public function getPostData()
    {
        return $this->postData;
    }

    /**
     * Returns request URI.
     * @access public
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Returns data of $_SESSION array.
     * @access public
     * @return array
     */
    public function getSessionData()
    {
        return $this->sessionData;
    }

    /**
     * Returns an element of one of the arrays ($_GET, $_POST, $_SESSION and http request headers)
     * @param string $arrayName
     * @param string $element
     * @return mixed|bool Returns false if element does not exists
     */
    public function getElement($arrayName, $element)
    {
        $action = 'get'.ucfirst($arrayName).'Data';
        $dataArray = $this->$action();
        if (isset($dataArray[$element])) {
            return $dataArray[$element];
        }
        return false;
    }
}