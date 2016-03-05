<?php

return array(
    'home'           => array(
        '_name'      => 'home',
        'pattern'    => '/',
        'controller' => 'Blog\\Controller\\PostController',
        'action'     => 'index'
    ),
    'testredirect'   => array(
        '_name'      => 'testredirect',
        'pattern'    => '/test_redirect',
        'controller' => 'Blog\\Controller\\TestController',
        'action'     => 'redirect',
    ),
    'test_json'      => array(
        '_name'      => 'test_json',
        'pattern'    => '/test_json',
        'controller' => 'Blog\\Controller\\TestController',
        'action'     => 'getJson',
    ),
    'signin'         => array(
        '_name'      => 'signin',
        'pattern'    => '/signin',
        'controller' => 'Blog\\Controller\\SecurityController',
        'action'     => 'signin'
    ),
    'login'          => array(
        '_name'      => 'login',
        'pattern'    => '/login',
        'controller' => 'Blog\\Controller\\SecurityController',
        'action'     => 'login'
    ),
    'logout'         => array(
        '_name'      => 'logout',
        'pattern'    => '/logout',
        'controller' => 'Blog\\Controller\\SecurityController',
        'action'     => 'logout'
    ),
    'update_profile' => array(
        '_name'      => 'update_profile',
        'pattern'       => '/profile',
        'controller'    => 'CMS\\Controller\\ProfileController',
        'action'        => 'update',
        '_requirements' => array(
            '_method' => 'POST'
        )
    ),
    'profile'        => array(
        '_name'      => 'profile',
        'pattern'    => '/profile',
        'controller' => 'CMS\\Controller\\ProfileController',
        'action'     => 'get'
    ),
    'add_post'       => array(
        '_name'      => 'add_post',
        'pattern'    => '/posts/add',
        'controller' => 'Blog\\Controller\\PostController',
        'action'     => 'add',
        'security'   => array('ROLE_USER'),
    ),
    'show_post'      => array(
        '_name'      => 'show_post',
        'pattern'       => '/posts/{id}',
        'controller'    => 'Blog\\Controller\\PostController',
        'action'        => 'show',
        '_requirements' => array(
            'id' => '\d+'
        )
    ),
    'edit_post'      => array(
        '_name'      => 'edit_post',
        'pattern'       => '/posts/{id}/edit',
        'controller'    => 'CMS\\Controller\\BlogController',
        'action'        => 'edit',
        '_requirements' => array(
            'id'      => '\d+',
            '_method' => 'POST'
        )

    ),
    'my_route_args'  => array(
        '_name'      => 'my_route_args',
        'pattern'       => '/my_route/{id}/{name}',
        'controller'    => 'Blog\\Controller\\MyController',
        'action'        => 'my',
        '_requirements' => array(
            'id'   => '\d+',
            'name' => '\w+',
        )
    ),
    'my_route_post'  => array(
        '_name'      => 'my_route_post',
        'pattern'       => '/my_route/{id}/{name}',
        'controller'    => 'Blog\\Controller\\MyController',
        'action'        => 'post',
        '_requirements' => array(
            '_method' => 'POST',
            'id'      => '\d+',
            'name'    => '\w+',
        )
    ),
    'my_route'       => array(
        '_name'      => 'my_route',
        'pattern'    => '/my_route',
        'controller' => 'Blog\\Controller\\MyController',
        'action'     => 'simple',
    ),
);