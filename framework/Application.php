<?php
namespace Framework;

use Framework\Exception\DataBaseException;
use Framework\Exception\RouterException;
use Framework\Registry\Registry;
use Framework\Request\Request;
use Framework\Router\Router;

class Application
{
    public static $counter;

    public function run()
    {
        $registry            = Registry::getInstance();
        $registry['request'] = new Request();
        $registry['config']  = require_once('../app/config/config.php');

        $router = new Router();

        try{
            $router->getRoute();
        } catch (DataBaseException $e){
            echo $e;
        } catch (RouterException $e){
            echo $e;
        } catch (\Exception $e){
            echo$e;
        }
        echo '<pre>';

        echo '</pre>';
    }
}