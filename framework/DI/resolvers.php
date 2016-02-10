<?php
return array(
    'security' => function () {
        $security = \Framework\Registry\Registry::getInstance()['config']['security'];
        if (isset($security)) {
            $securityObj = new $security['user_class']();
            if($securityObj instanceof \Framework\Security\Model\UserInterface){
                return $securityObj;
            }
        }
        return null;
    }
);