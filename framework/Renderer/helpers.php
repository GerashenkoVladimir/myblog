<?php

//"helpers" for rendering
return array(
    'getRoute'      => function ($route) {
        return \Framework\DI\Service::get('router')->generateURL($route);
    },
    'generateToken' => function () {
        $token = md5(rand());
        $session = \Framework\DI\Service::get('session');
        $session->set('token', $token);
        echo "<input type='hidden' name='token' value='$token'>";
    },
    'user'          => \Framework\DI\Service::get('session')->get('user'),
    'route'         => \Framework\DI\Service::get('router')->getReadyRoute(),
    'flush'         => \Framework\DI\Service::get('flushMessenger')->getMessages(),
    'include'       => function($controllerName, $action, $args = array()){
        $response = \Framework\DI\Service::get('app')->getResponse($controllerName, $action.'Action', $args);
        $response->send();
    },
);