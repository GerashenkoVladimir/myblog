<?php


namespace Framework\Security;

use Framework\Registry\Registry;
use Framework\Security\Model\UserInterface;

class Security
{
    private $user;
    private $registry;

    public function __construct(UserInterface $user, Registry $registry)
    {
        $this->user = $user;
        $this->registry = $registry;
    }
    public function isAuthenticated()
    {
        $userFields = $this->user->getFieldsNames();
    }
}