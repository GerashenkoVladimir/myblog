<?php

return array(
    //Modes of of the framework
    'mode'        => 'dev', //user|dev
    //Route map
    'routes'      => include('routes.php'),
    //The directory that contains templates
    'layouts'     => __DIR__ . '/../../src/Blog/views/',
    //Main layout
    'main_layout' => __DIR__ . '/../../src/Blog/views/layout.html.php',
    //
    'error_500'   => __DIR__ . '/../../src/Blog/views/500.html.php',
    'security'    => array(
        'user_class'  => 'Blog\\Model\\User',
        'login_route' => 'login'
    ),
    'pdo'         => include('dbConfig.php'),
);