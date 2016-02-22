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

    public function findByEmail($email)
    {
        self::initResources();
        echo '<pre>';
        var_dump($user = self::$database->select(self::getTable(), array('*'), array('email' => $email)));
        foreach ($user as $u => $value){
            $this->$u = $value;
        }


        echo $this->id;
        echo $this->email;
        echo $this->password;
        echo $this->role;
        echo '</pre>';
        return $this;

    }

    private static function initResources()
    {
        if (!isset(self::$sessions)) {
            self::$sessions = self::$registry['sessions'];
        }

        if (!isset(self::$database)) {
            self::$database =self::$registry['dataBase'];
        }
    }
}