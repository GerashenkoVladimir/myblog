<?php
return array(
    'security' => function () {
        $security = \Framework\Registry\Registry::getInstance()['config']['security'];
        if (isset($security)) {
            return new $security['user_class']();
        }
        return null;
    }
);