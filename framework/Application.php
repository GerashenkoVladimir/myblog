<?php
namespace Framework;

use Framework\DataBase\DataBase;
use Framework\Exception\BadResponseException;
use Framework\Exception\DataBaseException;
use Framework\Exception\HTTPNotFoundException;
use Framework\Registry\Registry;
use Framework\Request\Request;
use Framework\Router\Router;
use Framework\Sessions\Sessions;
use Framework\Response\Response;


class Application
{
    private $registry;
    private $router;

    public function __construct()
    {
        $this->registry             = Registry::getInstance();
        $this->registry['sessions'] = new Sessions();
        $this->registry['request']  = new Request();
        $this->registry['config']   = require_once('../app/config/config.php');
        $this->registry['dataBase'] = DataBase::getInstance();
        $this->registry['router']   = $this->router = new Router();
    }

    public function run()
    {


        try{
            $route = $this->router->getRoute();
            if ($route == null) {
                throw new HTTPNotFoundException("ERROR 404!!! Page not found!");
            }
            $response = $this->getResponse($route['controller'],$route['action'],$route['args']);
            if (!$response instanceof Response) {
                throw new BadResponseException('Wrong type of Response!');
            }
            //добавить обработку ошибок.
        } catch (DataBaseException $e){
            echo $e;
        } catch (HTTPNotFoundException $e){
            echo $e;
        } catch (\Exception $e){
            echo $e;
        }
        $response->send();
    }

    /**
     * Creates object of controller, launches action and returns Response object.
     *
     * @access private
     *
     * @param string $controllerName
     * @param string $action
     * @param array  $args
     *
     * @throws HTTPNotFoundException
     * @return Response|string
     */
    private function getResponse($controllerName, $action, $args)
    {
        if (!method_exists($controllerName, $action)) {
            throw new HTTPNotFoundException("Class \"$controllerName\" or action \"$action\" not exists!");
        }
        $controllerObj = new $controllerName();
        return call_user_func_array(array($controllerObj, $action), $args);
    }
}