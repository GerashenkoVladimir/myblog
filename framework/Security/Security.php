<?php


namespace Framework\Security;

use Framework\DI\Service;
use Framework\Exception\SecurityException;
use Framework\Security\Model\UserInterface;
use Framework\Sessions\Sessions;

/**
 * Class Security
 * @package Framework\Security
 */
class Security
{

    /**
     * Sessions instance
     *
     * @access private
     *
     * @var Sessions
     */
    private $session;

    /**
     * Security constructor
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
     * Sets user
     *
     * @access public
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function setUser(UserInterface $user)
    {
        $this->session->set('user', $user);
    }

    /**
     * Clears user
     *
     * @access public
     *
     * @return void
     */
    public function clear()
    {
        $this->session->unsetParam('user');
    }

    /**
     * Checks authentication
     *
     * @access public
     *
     * @return bool
     * @throws SecurityException
     */
    public function isAuthenticated()
    {
        if (!is_null($user = $this->session->get('user'))) {
            if (!$user instanceof UserInterface) {
                throw new SecurityException("Your \"user class\" must to be instance of
                    Framework\\Security\\Model\\UserInterface");
            }
            $fields = $user->getFieldsNames();
            foreach ($fields as $field) {
                if (is_null($user->$field)) {

                    return false;
                }
            }

            return true;
        }

        return false;
    }
}