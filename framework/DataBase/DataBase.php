<?php

namespace Framework\DataBase;

use Framework\Exception\DataBaseException;
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
        try {
            $this->connection = new \PDO($this->dbConfig['dns'], $this->dbConfig['user'], $this->dbConfig['password']);
        } catch (\PDOException $e) {
            echo $e;
        }
    }

    public function selectAll($table, $orderBy = null)
    {
        $queryString = "SELECT * FROM $table";
        $queryString = $this->getOrderBy($queryString,$orderBy);

        return $this->connection->query($queryString)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function select($table, $displayParam, $compareData, $orderBy = null)
    {
        try {
            if (!is_array($displayParam) || !is_array($compareData)) {
                throw new DataBaseException('Error! $displayParam, $compareData parameters must be arrays!');
            }

            $readyDisplayString = '';
            foreach ($displayParam as $param) {
                $readyDisplayString .= $param . ', ';
            }
            $readyDisplayString = rtrim($readyDisplayString, ', ');

            $readyCompareData = $this->prepareData($compareData, ' AND ');

            $queryString = "SELECT $readyDisplayString FROM $table WHERE {$readyCompareData['placeholderString']}";

            $queryString = $this->getOrderBy($queryString, $orderBy);

            $pdoStatement = $this->connection->prepare($queryString);
            $pdoStatement->execute($readyCompareData['values']);

            return $pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (DataBaseException $e) {
            echo $e;
        }
    }

    public function insert($table, $data)
    {
        try {
            if (!is_array($data)) {
                throw new DataBaseException('Error! $data parameter should be an array!');
            }
            $readyData = $this->prepareInsertData($data);
            $queryString = "INSERT INTO $table ({$readyData['params']}) VALUES ({$readyData['placeholders']})";
            $pdoStatement = $this->connection->prepare($queryString);
            $pdoStatement->execute($readyData['values']);
        } catch (DataBaseException $e) {
            echo $e;
        }
    }

    public function update($table, $updateData, $compareData)
    {
        $readyUpdateData = $this->prepareData($updateData, ', ');
        $readyCompareData = $this->prepareData($compareData, ' AND ');
        $values = array_merge($readyUpdateData['values'], $readyCompareData['values']);
        $queryString = "UPDATE $table SET {$readyUpdateData['placeholderString']} WHERE {$readyCompareData['placeholderString']}";
        $pdoStatement = $this->connection->prepare($queryString);
        $pdoStatement->execute($values);
    }

    public function delete($table, $comparedData)
    {
        $readyCompareData = $this->prepareData($comparedData, ' AND ');
        $queryString = "DELETE FROM $table WHERE {$readyCompareData['placeholderString']}";
        $pdoStatement = $this->connection->prepare($queryString);
        $pdoStatement->execute($readyCompareData['values']);
    }

    private function getOrderBy($queryString, $orderBy)
    {
        if (!is_null($orderBy)) {
            $queryString .= " ORDER BY $orderBy";
        }
        return $queryString;
    }

    private function prepareInsertData($data)
    {
        $readyData = array(
            'params' => '',
            'values' => array(),
        );

        foreach ($data as $param => $value) {
            $readyData['params'] .= $param . ', ';
            array_push($readyData['values'], $value);
        }

        $readyData['params'] = rtrim($readyData['params'], ', ');

        $readyData['placeholders'] = '';
        $count = count($data);
        for ($i = 1; $i <= $count; $i++) {
            $readyData['placeholders'] .= '?, ';
        }

        $readyData['placeholders'] = rtrim($readyData['placeholders'], ', ');

        return $readyData;
    }

    private function prepareData($data, $separator = '')
    {
        $readyData = array(
            'placeholderString' => '',
            'values'            => array(),
        );

        foreach ($data as $param => $value) {
            $readyData['placeholderString'] .= $param . '=?' . $separator;
            array_push($readyData['values'], $value);
        }

        $readyData['placeholderString'] = rtrim($readyData['placeholderString'], $separator);

        return $readyData;
    }
}