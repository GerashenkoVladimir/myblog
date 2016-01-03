<?php

namespace Framework\Exceptions;

class RouterExceptions extends \Exception
{
    const ERROR_404 = 0;
    const FILE_OR_ACTION_NOT_FOUND = 1;

    public function __construct($code, $args = array())
    {
        switch ($code) {
            case self::FILE_OR_ACTION_NOT_FOUND:
                $message = $this->getNotFoundMessage($args[0], $args[1]);
                break;
            case self::ERROR_404:
                $message = $this->get404Message();
                break;
            default:
                $message = "Server error!";
                break;
        }
        parent::__construct($message);
    }

    public function __toString()
    {
        return $this->message;
    }

    private function getNotFoundMessage($controllerName, $action)
    {
        return "File \"$controllerName\" or action \"$action\" not found!!!";
    }

    private function get404Message()
    {
        return "Error: 404!!! Page not found!";
    }
}