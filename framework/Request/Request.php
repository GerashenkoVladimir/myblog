<?php

namespace Framework\Request;

class Request
{
    private $uri;

    private $requestMethod;

    private $getData;

    private $postData;

    public function __construct()
    {
        $this->uri           = $_SERVER['REQUEST_URI'];
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->getData       = $_GET;
        $this->postData      = $_POST;
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
}