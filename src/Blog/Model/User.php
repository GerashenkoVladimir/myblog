<?php

namespace Blog\Model;

use Framework\DI\Service;
use Framework\Model\ActiveRecord;
use Framework\Security\Model\UserInterface;

/**
 * Class User
 * @package Blog\Model
 */
class User extends ActiveRecord implements UserInterface
{
    /**
     * User id
     *
     * @access public
     *
     * @var string
     */
    public $id;

    /**
     * User email
     *
     * @access public
     *
     * @var string
     */
    public $email;

    /**
     * User password
     *
     * @access public
     *
     * @var string
     */
    public $password;

    /**
     * User role
     *
     * @access public
     *
     * @var string
     */
    public $role;

    /**
     * User constructor
     *
     * @access public
     *
     * @param array $record
     */
    public function __construct($record = array())
    {
        parent::__construct($record);
    }

    /**
     * Returns table name
     *
     * @access public
     *
     * @return string
     */
    public static function getTable()
    {
        return 'users';
    }

    /**
     * Returns user role
     *
     * @access public
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Returns fields for saving
     *
     * @access public
     *
     * @return array
     */
    public static function getFieldsNames()
    {
        return array('id', 'email', 'password', 'role');
    }

    /**
     * Finds user by email and returns User class object
     *
     * @param string $email
     *
     * @return User
     * @throws \Framework\Exception\DataBaseException
     * @throws \Framework\Exception\ServiceException
     */
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