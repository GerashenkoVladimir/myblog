<?php

namespace Framework\Response;

use Framework\DI\Service;

class Response
{
    protected $headers = array();
    private $statusCode;
    private $content;

    public function __construct($content, $headers = array(), $statusCode = 200)
    {
        $this->content    = $content;
        $this->headers    = $headers;
        $this->statusCode = $statusCode;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header)
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
        header(Service::get('request')->getServerProtocol()." $this->statusCode");

        foreach ($this->headers as $header) {
            header($header);
        }

        if (!is_null($this->content)) {
            echo $this->content;
        }


    }
}