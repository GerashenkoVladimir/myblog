<?php

return array(
    'mode'        => 'dev',
    'routes'      => include('routes.php'),
    'main_layout' => __DIR__.'/../../src/Blog/views/layout.html.php',
    'error_500'   => __DIR__.'/../../src/Blog/views/500.html.php',
    /*'pdo'         => array(
        'dns'      => 'mysql:dbname=education;host=192.168.64.15',
        'user'     => 'education',
        'password' => 'n29OB4uIYGii'
    ),*/
    'security'    => array(
        'user_class'  => 'Blog\\Model\\User',
        'login_route' => 'login'
    ),
    'pdo'         => array(
        'dns'      => 'mysql:dbname=education;host=127.0.0.1',
        'user'     => 'education',
        'password' => 'gfhjkm1987'
    ),
);