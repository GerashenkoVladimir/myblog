<?php

namespace Framework\Response;

/**
 * Class ResponseRedirect
 * @package Framework\Response
 */
class ResponseRedirect extends Response
{
    /**
     * ResponseRedirect constructor
     *
     * @access public
     *
     * @param string $route Redirect route
     */
    public function __construct($route)
    {
        array_push($this->headers, "Location: $route");
    }

}