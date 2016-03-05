<?php
return array(
    'getRoute' => function($route){
        return \Framework\DI\Service::get('router')->generateURL($route);
    },
    'generateToken' => function(){
        $token = md5(rand());
        $session = \Framework\DI\Service::get('session');
        $user = $session->get('user');
        /*if($user == null){
            $session->set('user', array('token' => $token));
        } else {
            $user['token'] = $token;
            $session->set('user', $user);
        }*/
        echo "<input type='hidden' name='token' value='$token'>";
    },
    'user' => \Framework\DI\Service::get('session')->get('user'),
    'route' => \Framework\DI\Service::get('router')->getReadyRoute(),
    'flush' => \Framework\DI\Service::get('session')->has('flush') ? \Framework\DI\Service::get('session')->get('flush')
        : array(),
    /*'flush' => array('info' => array('Some message 1!'),
            'danger' => array('Some message 2!'),
            'link' => array('Some message 3!'),
            'success' => array('Some message 4!'),
            'warning' => array('Some message 5!'),
        ),*/
);