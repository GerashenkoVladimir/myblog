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
        self::initResources();
    }

    public static function getTable()
    {
        return 'users';
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getFieldsNames()
    {
        return array('id', 'email', 'role', 'token');
    }

    public function findByEmail($email)
    {
        self::initResources();
        $user = self::$database->select(self::getTable(), array('*'), array('email' => $email));
        foreach ($user as $u => $value){
            $this->$u = $value;
        }

        return $this;
    }

    private static function initResources()
    {
        /*if (!isset(self::$sessions)) {
            self::$sessions = Service::get('registry')['sessions'];
        }*/

        if (!isset(self::$database)) {
            self::$database = Service::get('dataBase');
        }
    }
}