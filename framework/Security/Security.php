<?php


namespace Framework\Security;

use Framework\DI\Service;
use Framework\Exception\SecurityException;
use Framework\Security\Model\UserInterface;

class Security
{

    private $session;

    public function __construct()
    {
        $this->session = Service::get('session');
    }

    public function setUser(UserInterface $user)
    {
        $this->session->set('user', $user);
    }

    public function clear()
    {
        $this->session->unsetParam('user');
    }
    
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