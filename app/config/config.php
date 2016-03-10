<?php

return array(
    'mode'              => 'dev', //user|dev
    'routes'            => include('routes.php'),
    'main_layout'       => __DIR__.'/../../src/Blog/views/layout.html.php',
    'layouts'           => __DIR__.'/../../src/Blog/views/',
    'error_500'         => __DIR__.'/../../src/Blog/views/500.html.php',
    'security'          => array(
        'user_class'  => 'Blog\\Model\\User',
        'login_route' => 'login'
    ),
    'pdo' => include('dbConfig.php'),
);