<?php

namespace Blog\Model;

use Framework\DI\Service;
use Framework\Model\ActiveRecord;
use Framework\Security\Model\UserInterface;

class User extends ActiveRecord implements UserInterface
{
    public $id;
    public $email;
    public $password;
    public $role;

    public function __construct($record = array())
    {
        parent::__construct($record);
    }

    public static function getTable()
    {
        return 'users';
    }

    public function getRole()
    {
        return $this->role;
    }

    public static function getFieldsNames()
    {
        return array('id', 'email', 'password', 'role');
    }

    public static function findByEmail($email)
    {
        if (!isset(self::$database)) {
            self::$database = Service::get('dataBase');
        }
        $user = self::$database->select(self::getTable(), array('*'), array('email' => $email));
        $userObj = new self();
        foreach ($user as $u => $value){
            $userObj->$u = $value;
        }

        return $userObj;
    }
}