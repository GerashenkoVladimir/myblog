<?php


namespace Framework\DataBase;

use Framework\Registry\Registry;

class DataBase
{
    private $registry;

    private $dbConfig;

    private $connection;

    /**
     * Object variable of the DataBase class
     * @access private
     * @var DataBase
     */
    private static $_instance;

    /**
     * Implements pattern Singlton. Create and return object variable of DataBase class.
     * @access public
     * @return DataBase
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    protected function __construct()
    {
        $this->registry = Registry::getInstance();
        $this->dbConfig = $this->registry['config']['pdo'];
        $this->connection = new \PDO($this->dbConfig['dns'], $this->dbConfig['user'],$this->dbConfig['password']);
    }
}