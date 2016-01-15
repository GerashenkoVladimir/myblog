<?php

namespace Framework;

use Framework\DataBase\DataBase;
use Framework\Registry\Registry;
use Framework\Request\Request;
use Framework\Router\Router;

class Application
{
    public static $counter;

    public function run()
    {
        self::$counter++;
        $registry            = Registry::getInstance();
        $registry['request'] = new Request();
        $registry['config']  = require_once('../app/config/config.php');

        $router = new Router();
        $router->getRoute();

        echo '<pre>';
        $dataBase = DataBase::getInstance();
        $data = array(
            'firstName' => 'Sergey',
            'lastName' => 'Gerashenko',
        );
        $table = 'test';

        $dataBase->insert($table, $data);

        echo '</pre>';
    }
}