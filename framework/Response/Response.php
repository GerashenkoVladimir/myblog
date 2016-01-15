<?php

namespace Framework\Response;

use Framework\Registry\Registry;

class Response
{
    private $headers;
    private $statusCode;
    private $content;
    private $registry;

    public function __construct($headers = array(), $statusCode = 200, $content)
    {
        $this->registry   = Registry::getInstance();
        $this->headers    = $headers;
        $this->statusCode = $statusCode;
        $this->content    = $content;
    }

    /**
     * @param mixed $header
     */
    public function setHeaders($header)
    {
        array_push($this->headers, $header);
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function send()
    {
        header("{$this->registry['request']->getServerProtocol()} $this->statusCode");

        foreach ($this->headers as $header) {
            $header($header);
        }

        echo $this->content;
    }
}