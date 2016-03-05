<?php

namespace Framework\Response;


use Framework\DI\Service;

class ResponseRedirect extends Response
{
    public function __construct($route, $message = '')
    {
        array_push($this->headers, "Location: $route");
        Service::get('flushMessenger', array($message));
    }

}