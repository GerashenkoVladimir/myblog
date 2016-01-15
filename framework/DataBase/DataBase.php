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
     *
     * @access private
     * @var DataBase
     */
    private static $_instance;

    /**
     * Implements pattern Singlton. Create and return object variable of DataBase class.
     *
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

    private function __construct()
    {
        $this->registry = Registry::getInstance();
        $this->dbConfig = $this->registry['config']['pdo'];
        try{
            $this->connection = new \PDO($this->dbConfig['dns'], $this->dbConfig['user'], $this->dbConfig['password']);
        } catch (\PDOException $e){
            echo $e->getMessage();
        }
    }

    public function insert($table, $data = array())
    {
        $readyData    = $this->prepareInsertData($data);
        $queryString  = "INSERT INTO $table ({$readyData['params']}) VALUES ({$readyData['placeholders']})";
        $pdoStatement = $this->connection->prepare($queryString);
        $pdoStatement->execute($readyData['values']);
    }

    public function update($table, $data)
    {

        $queryString = "UPDATE $table SET name='PHP', hide=1 WHERE id_forum=2";
    }

    private function prepareInsertData($data)
    {
        $readyData = array(
            'params' => '',
            'values' => array(),
        );

        foreach ($data as $param => $value) {
            $readyData['params'] .= $param.', ';
            array_push($readyData['values'], $value);
        }

        $readyData['params'] = rtrim($readyData['params'], ', ');

        $readyData['placeholders'] = '';
        $count = count($data);
        for ($i = 1;$i <= $count;$i++) {
            $readyData['placeholders'] .= '?, ';
        }

        $readyData['placeholders'] = rtrim($readyData['placeholders'], ', ');

        return $readyData;
    }

    private function prepareUpdateData()
    {

    }
}