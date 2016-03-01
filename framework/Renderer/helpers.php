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
);