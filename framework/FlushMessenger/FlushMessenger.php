<?php
namespace Framework\FlushMessenger;

use Framework\DI\Service;
use Framework\Sessions\Sessions;

/**
 * Class FlushMessenger
 * @package Framework\FlushMessenger
 */
class FlushMessenger
{

    /**
     * @const
     * @var string
     */
    const INFO = 'info';

    /**
     * @const
     * @var string
     */
    const DANGER = 'danger';

    /**
     * @const
     * @var string
     */
    const SUCCESS = 'success';

    /**
     * @const
     * @var string
     */
    const WARNING = 'warning';

    /**
     * Session instance
     *
     * @access private
     *
     * @var mixed|Sessions
     */
    private $session;

    /**
     * FlushMessenger constructor
     *
     * @access public
     *
     * @throws \Framework\Exception\ServiceException
     */
    public function __construct()
    {
        $this->session = Service::get('session');
    }

    /**
     * Sets flush message
     *
     * @access public
     *
     * @param string $message
     * @param string $type
     *
     * @return void
     */
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

    /**
     * Returns flush messages
     *
     * @access public
     *
     * @return array
     */
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