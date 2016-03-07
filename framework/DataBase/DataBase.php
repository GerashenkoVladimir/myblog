<?php

namespace Framework\DataBase;

use Framework\DI\Service;
use Framework\Exception\DataBaseException;
use Framework\Inheritance\Singleton;

/**
 * Class DataBase
 * Implements a convenient connection to the database, and some of the functions to work with the database
 *
 * @package Framework\DataBase
 */
class DataBase extends Singleton
{
    /**
     * Database configurations
     *
     * @access private
     * @var array
     */
    private $dbConfig;

    /**
     * Object variable of connection to the database
     *
     * @access private
     * @var \PDO
     */
    private $connection;


    /**
     * Implements pattern Singleton. Create and return DataBase class variable
     *
     * @access public
     *
     * @return DataBase
     */
    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(static::$_instance[$class])) {
            static::$_instance[$class] = new $class();
        }

        return static::$_instance[$class];
    }

    protected function __construct()
    {
        $this->dbConfig = Service::get('config')['pdo'];
        try{
            $this->connection = new \PDO($this->dbConfig['dns'], $this->dbConfig['user'], $this->dbConfig['password']);
        } catch (\PDOException $e){
            echo "<pre>$e</pre>";
        }
    }

    /**
     * Generates the query string and returns all rows contained in the table
     *
     * @access public
     *
     * @param string      $table   Name of the table
     * @param null|string $orderBy Sorting option
     *
     * @throws DataBaseException
     * @return array Returns a two-dimensional array of data
     */
    public function selectAll($table, $orderBy = null)
    {
        if (!is_string($table) || !(is_string($orderBy) || is_null($orderBy))) {
            throw new DataBaseException('Error! Wrong type of parameters $table or $orderBy!');
        }

        $queryString = "SELECT * FROM $table";
        $queryString = $this->getOrderBy($queryString, $orderBy);
        $result      = $this->connection->query($queryString);
        if (!$result) {
            throw new DataBaseException('Bad request to database!');
        }

        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Generates the query string and returns rows contained in the table
     *
     * @access public
     *
     * @param string      $table        Name of the table
     * @param array       $displayParam The fields to be displayed
     * @param array       $compareData  The data to be compared (param => value)
     * @param null|string $orderBy      Sorting option
     *
     * @throws DataBaseException
     * @return array Returns a two-dimensional array of data
     */
    public function select($table, $displayParam, $compareData, $orderBy = null)
    {
        if (!is_string($table) || !is_array($displayParam) || !is_array($compareData)
            || !(is_string($orderBy) || is_null($orderBy))
        ) {
            throw new DataBaseException('Error! Wrong type of parameters $table, $displayParam, $compareData or $orderBy');
        }

        $readyDisplayString = '';
        foreach ($displayParam as $param) {
            $readyDisplayString .= $param.', ';
        }
        $readyDisplayString = rtrim($readyDisplayString, ', ');

        $readyCompareData = $this->prepareData($compareData, ' AND ');

        $queryString = "SELECT $readyDisplayString FROM $table WHERE {$readyCompareData['placeholderString']}";
        $queryString = $this->getOrderBy($queryString, $orderBy);

        $pdoStatement = $this->connection->prepare($queryString);
        $pdoStatement->execute($readyCompareData['values']);
        $result = $pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
        if (count($result) > 1 || count($result) == 0){
            return $result;
        }

        return $result[0];
    }

    /**
     * Generates the query string and adds a row in the database table
     *
     * @access public
     *
     * @param string $table Name of the table
     * @param array  $data  Data to be added
     *
     * @throws DataBaseException
     * @return void
     */
    public function insert($table, $data)
    {
        if (!is_string($table) || !is_array($data)) {
            throw new DataBaseException('Error! Wrong type of parameters $table or $data!');
        }
        $readyData    = $this->prepareInsertData($data);
        $queryString  = "INSERT INTO $table ({$readyData['params']}) VALUES ({$readyData['placeholders']})";
        $pdoStatement = $this->connection->prepare($queryString);
        $pdoStatement->execute($readyData['values']);
    }

    /**
     * Generates the query string and update a row in the database table
     *
     * @access public
     *
     * @param string $table       Name of the table
     * @param array  $updateData  The data to be updated (param => value)
     * @param array  $compareData The field to be compared (param => value)
     *
     * @throws DataBaseException
     * @return void
     */
    public function update($table, $updateData, $compareData)
    {
        if (!is_string($table) || !is_array($updateData) || !is_array($compareData)) {
            throw new DataBaseException('Error! Wrong type of parameters $table, $updateData or $compareDate!');
        }
        $readyUpdateData  = $this->prepareData($updateData, ', ');
        $readyCompareData = $this->prepareData($compareData, ' AND ');
        $values           = array_merge($readyUpdateData['values'], $readyCompareData['values']);
        $queryString      = "UPDATE $table SET {$readyUpdateData['placeholderString']} WHERE {$readyCompareData['placeholderString']}";
        $pdoStatement     = $this->connection->prepare($queryString);
        $pdoStatement->execute($values);
    }

    /**
     * Generates the query string and delete a row in the database table
     *
     * @param string $table        Name of the table
     * @param array  $comparedData The data to be deleted
     *
     * @throws DataBaseException
     * @return void
     */
    public function delete($table, $comparedData)
    {
        if (!is_string($table) || !is_array($comparedData)) {
            throw new DataBaseException('Error! Wrong type of parameters $table or $compareData!');
        }
        $readyCompareData = $this->prepareData($comparedData, ' AND ');
        $queryString      = "DELETE FROM $table WHERE {$readyCompareData['placeholderString']}";
        $pdoStatement     = $this->connection->prepare($queryString);
        $pdoStatement->execute($readyCompareData['values']);
    }

    /**
     * Check the "order status" and generate query string with order options
     *
     * @access private
     *
     * @param string      $queryString String of SQL query
     * @param string|null $orderBy     "ORDER BY" parameter
     *
     * @return string String of SQL query
     */
    private function getOrderBy($queryString, $orderBy)
    {
        if (!is_null($orderBy)) {
            $queryString .= " ORDER BY $orderBy";
        }

        return $queryString;
    }

    /**
     * Prepare data for insert query
     *
     * @param array $data Data for insert
     *
     * @return array Data prepared for insert into the database
     */
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
        $count                     = count($data);
        for ($i = 1;$i <= $count;$i++) {
            $readyData['placeholders'] .= '?, ';
        }
        $readyData['placeholders'] = rtrim($readyData['placeholders'], ', ');

        return $readyData;
    }

    /**
     * Prepare string with params and placeholders (param1=?, param2=?) and values
     *
     * @param string $data
     * @param string $separator
     *
     * @return array
     */
    private function prepareData($data, $separator = '')
    {
        $readyData = array(
            'placeholderString' => '',
            'values'            => array(),
        );

        foreach ($data as $param => $value) {
            $readyData['placeholderString'] .= $param.'=?'.$separator;
            array_push($readyData['values'], $value);
        }

        $readyData['placeholderString'] = rtrim($readyData['placeholderString'], $separator);

        return $readyData;
    }
}