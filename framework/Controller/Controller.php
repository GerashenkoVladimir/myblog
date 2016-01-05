<?php

namespace Framework\Controller;

use Framework\Registry\Registry;

abstract class Controller
{
    protected $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }
}