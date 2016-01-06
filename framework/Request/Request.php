<?php

namespace Framework\Request;

use Framework\Registry\Registry;

class Request
{
    private $registry;

    private $allHeaders = array();

    private $uri;

    private $requestMethod;

    private $getData = array();

    private $postData = array();

    private $sessionData = array();

    public function __construct()
    {
        $this->registry      = Registry::getInstance();
        $this->allHeaders    = getallheaders();
        session_start();
        $this->uri           = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->sessionData   = $_SESSION;
        //добавить валидацию
        $this->getData       = $_GET;
        $this->postData      = $_POST;
    }

    public function getHeaders()
    {
        return $this->allHeaders;
    }

    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    public function getGetData()
    {
        return $this->getData;
    }

    public function getPostData()
    {
        return $this->postData;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getSessionData()
    {
        return $this->sessionData;
    }

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