<?php

namespace Blog\Model;

use Framework\Model\ActiveRecord;
use Framework\Security\Model\UserInterface;

class User extends ActiveRecord implements UserInterface
{
    public $id;
    public $email;
    public $password;
    public $role;

    private static $sessions;

    public function __construct()
    {
        parent::__construct();
        if (!isset(self::$sessions)) {
            self::$sessions = self::$registry['sessions'];
        }
    }

    public static function getTable()
    {
        return 'users';
    }

    public function getRole()
    {
        return $this->role;
    }

    public function isAuthenticated()
    {
        $logged = self::$sessions->get('logged');
        if ($logged == true) {
            return true;
        }
        return false;
    }

    public static function findByEmail($email)
    {
        var_dump(self::find(array('email'=>$email)));
        //return $user;
    }
}