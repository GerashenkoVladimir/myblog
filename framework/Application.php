<?php
namespace Framework;

use Framework\DataBase\DataBase;
use Framework\Exception\DataBaseException;
use Framework\Exception\RouterException;
use Framework\Registry\Registry;
use Framework\Request\Request;
use Framework\Router\Router;
use Framework\Sessions\Sessions;

class Application
{
    public static $counter;

    public function run()
    {
        $registry             = Registry::getInstance();
        $registry['sessions'] = new Sessions();
        $registry['request']  = new Request();
        $registry['config']   = require_once('../app/config/config.php');
        $registry['dataBase'] = DataBase::getInstance();
        $registry['router']   = $router = new Router();

        try{
            $router->getRoute();
        } catch (DataBaseException $e){
            echo $e;
        } catch (RouterException $e){
            echo $e;
        } catch (\Exception $e){
            echo $e;
        }
    }
}