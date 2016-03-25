<?php

namespace Framework\Request;

use Framework\DI\Service;
use Framework\Exception\RequestExceptions;

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

    /**
     * An associative array that contains the $_COOKIE array.
     *
     * @access private
     * @var array|string
     */
    private $cookieData = array();

    /**
     * Filter 'string' mode
     *
     * @const
     * @var string
     */
    const FILTER_STRING = 'string';

    /**
     * Filter 'int' mode
     *
     * @const
     * @var string
     */
    const FILTER_INT = 'int';

    Private $filterBag = array(
        Request::FILTER_STRING => array(
            'pattern' => '/[\w@-]+$/u',
            'message' => 'Please enter the letters, numbers or symbols @ , - , _!'
            ),
        Request::FILTER_INT => array(
            'pattern' => '/^\d+$/',
            'message' => 'Please enter the numbers!'
        ),
        'defaultMessage' => 'You have entered the wrong data'
    );

    /**
     * Filter 'skip' mode
     *
     * @const
     * @var string
     */
    const SKIP_FILTER = 'skip';

    /**
     * Request constructor
     *
     * @access public
     */
    public function __construct()
    {
        $this->allHeaders = getallheaders();
        $this->uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->serverProtocol = $_SERVER['SERVER_PROTOCOL'];
        $this->httpHost = $_SERVER['HTTP_HOST'];
        $this->getData = $this->cleanData($_GET);
        $this->postData = $this->cleanData($_POST);
        $this->cookieData = $this->cleanData($_COOKIE);
    }

    /**
     * Returns headers of http request.
     *
     * @access public
     *
     * @param string|null $key
     *
     * @return array|string|null
     */
    public function getHeaders($key = null)
    {
        if ($key == null) {
            return $this->allHeaders;
        }

        return isset($this->allHeaders[$key]) ? $this->allHeaders[$key] : null;
    }

    /**
     * Returns method of http request.
     *
     * @access public
     *
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
     *
     * @return boolean
     */
    public function isPost()
    {
        return $this->requestMethod == 'POST';
    }

    /**
     * Return "true" if request method is GET
     *
     * @access public
     *
     * @return boolean
     */
    public function isGet()
    {
        return $this->requestMethod == 'GET';
    }

    /**
     * Returns "true" if request is "ajax"
     *
     * @access public
     *
     * @return boolean
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * Returns server protocol.
     *
     * @access public
     *
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
     *
     * @return string
     */
    public function getHTTPHost()
    {
        return $this->httpHost;
    }

    /**
     * Returns data of $_GET array.
     *
     * @access public
     *
     * @param string|null $key
     *
     * @return array|string|null
     */
    public function get($key = null)
    {
        if ($key == null) {
            return $this->getData;
        }

        return isset($this->getData[$key]) ? $this->getData[$key] : null;
    }

    /**
     * Returns data of $_POST array.
     *
     * @access public
     *
     * @param string|null $key
     *
     * @return array|string|null
     */
    public function post($key = null)
    {
        if ($key == null) {
            return $this->postData;
        }

        return isset($this->postData[$key]) ? $this->postData[$key] : null;
    }

    /**
     * Returns request URI.
     *
     * @access public
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Checks token
     *
     * @param string $tokenName
     *
     * @return bool
     * @throws \Framework\Exception\ServiceException
     */
    public function checkToken($tokenName)
    {
        $sessionToken = Service::get('session')->get($tokenName);
        Service::get('session')->unsetParam($tokenName);
        return $this->post($tokenName) == $sessionToken;
    }

    private function filter($var, $filterMode = Request::FILTER_STRING, $toClean = true)
    {
        if ($toClean) {
            $var = $this->cleanString($var);
        }

        if (!$filterMode == Request::SKIP_FILTER) {
            if (array_key_exists($filterMode, $this->filterBag)) {
                if (!preg_match($this->filterBag[$filterMode]['pattern'], $var)) {
                    throw new RequestExceptions($this->filterBag[$filterMode]['message']);
                }
            } else {
                if (!preg_match($filterMode, $var)) {
                    throw new RequestExceptions($this->filterBag['defaultMessage']);
                }
            }
        }

        return $var;
    }

    /**
     * Clean data from all "dangerous" characters
     *
     * @access private
     *
     * @param array|string $data
     *
     * @return array|string
     */
    private function cleanData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $value = $this->cleanString($value);
            }
        } else {
            $data = $this->cleanString($data);
        }

        return $data;
    }

    /**
     * Clean string from all "dangerous" characters
     *
     * @access private
     *
     * @param string $data
     *
     * @return string
     */
    private function cleanString($data)
    {
        $data = stripslashes($data);
        $data = htmlentities($data);
        $data = strip_tags($data);

        return $data;
    }
}