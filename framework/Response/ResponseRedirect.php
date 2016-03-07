<?php

namespace Framework\Response;

class ResponseRedirect extends Response
{
    public function __construct($route)
    {
        array_push($this->headers, "Location: $route");

    }

}