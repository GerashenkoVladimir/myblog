<?php
return array(
    'getRoute' => function($route){
        return $this->registry['router']->generateURL($route);
    },
    'generateToken' => function(){
        $token = md5(rand());
        $session = \Framework\Sessions\Sessions::getInstance();
        $user = $session->get('user');
        if($user == null){
            $session->set('user', array('token' => $token));
        } else {
            $user['token'] = $token;
            $session->set('user', $user);
        }
        echo "<input type='hidden' name='token' value='$token'>";
    },

);