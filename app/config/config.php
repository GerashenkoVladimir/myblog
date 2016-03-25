<?php

return array(
    //Modes of of the framework
    'mode'        => 'user', //user|dev
    //Route map
    'routes'      => include('routes.php'),
    //The directory that contains templates
    'layouts'     => __DIR__ . '/../../src/Blog/views/',
    //Main template
    'main_layout' => __DIR__ . '/../../src/Blog/views/layout.html.php',
    //Error 500 template
    'error_500'   => __DIR__ . '/../../src/Blog/views/500.html.php',
    //Security class and route
    'security'    => array(
        'user_class'  => 'Blog\\Model\\User',
        'login_route' => 'login'
    ),
    //pdo settings
    'pdo'         => include('dbConfig.php'),
);