<?php
namespace Framework\FlushMessenger;

use Framework\DI\Service;

class FlushMessenger
{

    const INFO    = 'info';
    const DANGER  = 'danger';
    const SUCCESS = 'success';
    const WARNING = 'warning';

    public function __construct($message, $type = self::INFO)
    {
        $session = Service::get('session');
        if (!$session->has('flush')) {
            $session->set('flush', array($type => array($message)));
        } else {
            $flush = $session->get('flush');
            $flush[$type][] = $message;
            $session->set('flush', $flush);
        }
    }
}