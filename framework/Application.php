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
        $compareData = array(
            'firstName' => 'Sergey',
            'lastName' => 'Gerashenko',
        );
        $table = 'test';

        /*$dataBase->update($table, array('firstName' => 'Lidiya', 'lastName' => 'Gucenko'),array('id' => 11,
                                                                                                     'firstName' => 'Grigoriy',
                                                                                                     'lastName'  => 'Gerashenko'));*/
        //var_dump($dataBase->select('test', array('id', 'firstName'), array('lastName' => 'Gerashenko'), 'id'));

        $dataBase->delete($table, array('firstName' => 'Vladimir', 'lastName' => 'Gerashenko'));
        var_dump($dataBase->selectAll($table, 'id'));



        echo '</pre>';
    }
}