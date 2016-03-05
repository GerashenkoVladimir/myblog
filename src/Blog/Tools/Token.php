<?php

namespace Blog\Tools;


use Framework\DI\Service;

class Token
{
    public static function checkToken()
    {
        if (Service::get('request')->post('token') === Service::get('session')->get('token')) {
            return true;
        }
        return false;
    }
}