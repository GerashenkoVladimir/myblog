<?php
namespace Framework\FlushMessenger;

use Framework\DI\Service;

class FlushMessenger
{

    const INFO    = 'info';
    const DANGER  = 'danger';
    const SUCCESS = 'success';
    const WARNING = 'warning';

    private $session;

    public function __construct()
    {
        $this->session = Service::get('session');
    }

    public function setMessage($message, $type = self::INFO)
    {
        if (!$this->session->has('flush')) {
            $this->session->set('flush', array($type => array($message)));
        } else {
            $flush = $this->session->get('flush');
            $flush[$type][] = $message;
            $this->session->set('flush', $flush);
        }
    }

    public function getMessages()
    {
        $flush = array();

        if ($this->session->has('flush')) {
            $flush = $this->session->get('flush');
            $this->session->unsetParam('flush');
        }

        return $flush;
    }
}