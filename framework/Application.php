<?php
namespace Framework;

use Framework\DataBase\DataBase;
use Framework\DI\Service;
use Framework\Exception\BadControllerException;
use Framework\Exception\BadResponseException;
use Framework\Exception\DataBaseException;
use Framework\Exception\HTTPNotFoundException;
use Framework\Exception\SecurityException;
use Framework\Exception\ServiceException;
use Framework\Registry\Registry;
use Framework\Router\Router;
use Framework\Sessions\Sessions;
use Framework\Response\Response;


class Application
{
    private $router;

    public function __construct()
    {
        try {
            $registry = Registry::getInstance();
            $registry['config'] = require_once('../app/config/config.php');
            Service::set('request', 'Framework\Request\Request');
            Service::setSingleton('router', $this->router = new Router());
            Service::setSingleton('session', Sessions::getInstance());
            Service::setSingleton('registry', $registry);
            Service::setSingleton('dataBase', function(Registry $registry){
                return DataBase::getInstance($registry);
            }, array('registry'));
            Service::set('Framework\Security\Model\UserInterface', 'Blog\Model\User');
            Service::set('security', 'Framework\Security\Security',array('registry'));


        }catch (ServiceException $e){
            echo "<pre>$e</pre>";
        }catch(\Exception $e){
            echo "<pre>$e</pre>";
        }

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
            echo "<pre>$e</pre>";
        } catch (HTTPNotFoundException $e){
            echo "<pre>$e</pre>";
        } catch(BadControllerException $e){
            echo "<pre>$e</pre>";
        } catch(SecurityException $e){
            echo "<pre>$e</pre>";
        } catch (\Exception $e){
            echo "<pre>$e</pre>";
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
     * @throws \Exception
     * @return Response|string
     */
    private function getResponse($controllerName, $action, $args)
    {
        if (!method_exists($controllerName, $action)) {
            throw new HTTPNotFoundException("Class \"$controllerName\" or action \"$action\" not exists!");
        }
        $controllerRefObj = new \ReflectionClass($controllerName);
        $parent = $controllerRefObj->getParentClass();
        if (!$parent || $parent->getName() != 'Framework\Controller\Controller') {
            throw new BadControllerException("Your \"$controllerName\" class should inherit the \"Framework\\Controller\\
            Controller\" class!");
        }
        $controllerObj = new $controllerName();

        return call_user_func_array(array($controllerObj, $action), $args);
    }
}