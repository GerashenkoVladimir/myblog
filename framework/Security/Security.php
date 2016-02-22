<?php


namespace Framework\Security;

class Security
{
    public function __construct()
    {

        $userRefObj = new \ReflectionClass('Framework\Security\Model\UserInterface');

    }
    public function isAuthenticated()
    {
        return false;
    }
}