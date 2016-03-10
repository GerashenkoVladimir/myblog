<?php
namespace Framework;

use Framework\DataBase\DataBase;
use Framework\DI\Service;
use Framework\Exception\BadControllerException;
use Framework\Exception\BadResponseException;
use Framework\Exception\BadTokenException;
use Framework\Exception\DataBaseException;
use Framework\Exception\HttpNotFoundException;
use Framework\Exception\MainException;
use Framework\Exception\SecurityException;
use Framework\Exception\ServiceException;
use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Framework\Sessions\Sessions;
use Framework\Response\Response;

class Application
{
    private $router;
    private $config;

    public function __construct($config)
    {
        try{
            Service::setSimple('config', $this->config = require_once($config));
            Service::set('request', 'Framework\Request\Request');
            Service::setSingleton('router', $this->router = new Router());
            Service::setSingleton('session', Sessions::getInstance());
            Service::setSingleton('dataBase', DataBase::getInstance());
            Service::set('security', 'Framework\Security\Security');
            Service::setSingleton('flushMessenger', 'Framework\FlushMessenger\FlushMessenger');
        } catch (ServiceException $e){
        } catch (\Exception $e){
        } finally {
            if (isset($e)) {
                $code         = isset($code)?$code:500;
                $errorMessage = isset($errorMessage)?$errorMessage:'Sorry for the inconvenience. We are working
                to resolve this issue. Thank you for your patience.';
                $responce     = MainException::handleForUser($e, array('code' => $code, 'message' => $errorMessage));
                $responce->send();
            }
        }
    }

    public function run()
    {
        try{
            $route = $this->router->getRoute();

            if ($route == null) {
                throw new HttpNotFoundException("Page not found!", 404);
            }

            $response = $this->getResponse($route['controller'], $route['action'], $route['args']);

            if (!$response instanceof Response) {
                throw new BadResponseException('Wrong type of Response!');
            }
        } catch (DatabaseException $e){
            $errorMessage = 'Sorry for the inconvenience. We are working to resolve this issue.
            Thank you for your patience.';
            $code         = 500;
        } catch (HttpNotFoundException $e){
            $code         = $e->getCode();
            $errorMessage = 'Page not found!';
        } catch (BadControllerException $e){
            $errorMessage = 'Sorry for the inconvenience. We are working to resolve this issue.
            Thank you for your patience.';
            $code         = 500;
        } catch (BadTokenException $e){
            $errorMessage = $e->getMessage();
            $code         = $e->getCode();
        } catch (SecurityException $e){
            $errorMessage = 'Sorry for the inconvenience. We are working to resolve this issue.
            Thank you for your patience.';
            $code         = 500;
        } catch (BadResponseException $e){
            $errorMessage = 'Sorry for the inconvenience. We are working to resolve this issue.
            Thank you for your patience.';
            $code         = 500;
        } catch (\Exception $e){
            $errorMessage = 'Sorry for the inconvenience. We are working to resolve this issue.
            Thank you for your patience.';
            $code         = 500;
        } finally {
            if (isset($e) || !isset($response)) {
                $response = MainException::handleForUser($e, array('code' => $code, 'message' => $errorMessage));
            }
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
     * @throws HttpNotFoundException
     * @throws BadControllerException
     * @return Response|string
     */
    private function getResponse($controllerName, $action, $args)
    {
        if (!method_exists($controllerName, $action)) {
            throw new HttpNotFoundException("Class \"$controllerName\" or action \"$action\" not exists!", 404);
        }
        $controllerRefObj = new \ReflectionClass($controllerName);
        $parent           = $controllerRefObj->getParentClass();
        if (!$parent || $parent->getName() != 'Framework\Controller\Controller') {
            throw new BadControllerException("Your \"$controllerName\" class should inherit the \"Framework\\Controller\\
            Controller\" class!");
        }
        $controllerObj = new $controllerName();

        return call_user_func_array(array($controllerObj, $action), $args);
    }
}