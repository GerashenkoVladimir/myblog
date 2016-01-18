<?php

namespace Framework\DataBase;

use Framework\Exception\DataBaseException;
use Framework\Inheritance\Singleton;
use Framework\Registry\Registry;

/**
 * Class DataBase
 * Implements a convenient connection to the database, and some of the functions to work with the database
 *
 * @package Framework\DataBase
 */
class DataBase extends Singleton
{
    /**
     * Object of Registry class
     *
     * @access private
     * @var Registry
     */
    private $registry;

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

    protected function __construct()
    {
        $this->registry = Registry::getInstance();
        $this->dbConfig = $this->registry['config']['pdo'];
        try{
            $this->connection = new \PDO($this->dbConfig['dns'], $this->dbConfig['user'], $this->dbConfig['password']);
        } catch (\PDOException $e){
            echo $e;
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
        try{
            if (!is_string($table) || !(is_string($orderBy) || is_null($orderBy))) {
                throw new DataBaseException('Error! Wrong type of parameters $table or $orderBy!');
            }

            $queryString = "SELECT * FROM $table";
            $queryString = $this->getOrderBy($queryString, $orderBy);
            var_dump($result = $this->connection->query($queryString));
            if (!$result) {
                throw new DataBaseException('Bad request to database!');
            }

            return $result->fetchAll(\PDO::FETCH_ASSOC);
        } catch (DataBaseException $e){
            echo $e;
        }
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
        try{
            $flag = is_array($displayParam);
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

            return $pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (DataBaseException $e){
            echo $e;
        }
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
        try{
            if (!is_string($table) || !is_array($data)) {
                throw new DataBaseException('Error! Wrong type of parameters $table or $data!');
            }
            $readyData    = $this->prepareInsertData($data);
            $queryString  = "INSERT INTO $table ({$readyData['params']}) VALUES ({$readyData['placeholders']})";
            $pdoStatement = $this->connection->prepare($queryString);
            $pdoStatement->execute($readyData['values']);
        } catch (DataBaseException $e){
            echo $e;
        }
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
        try{
            if (!is_string($table) || !is_array($updateData) || !is_array($compareData)) {
                throw new DataBaseException('Error! Wrong type of parameters $table, $updateData or $compareDate!');
            }
            $readyUpdateData  = $this->prepareData($updateData, ', ');
            $readyCompareData = $this->prepareData($compareData, ' AND ');
            $values           = array_merge($readyUpdateData['values'], $readyCompareData['values']);
            $queryString      = "UPDATE $table SET {$readyUpdateData['placeholderString']} WHERE {$readyCompareData['placeholderString']}";
            $pdoStatement     = $this->connection->prepare($queryString);
            $pdoStatement->execute($values);
        } catch (DataBaseException $e){
            echo $e;
        }
    }

    /**
     *
     * @access public
     *
     * @param string $db_table
     * @param array  $comparedParam
     * @param array  $comparedValue The value to be deleted
     *
     * @return bool|\mysqli_result
     */

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
        try{
            if (!is_string($table) || !is_array($comparedData)) {
                throw new DataBaseException('Error! Wrong type of parameters $table or $compareDate!');
            }
            $readyCompareData = $this->prepareData($comparedData, ' AND ');
            $queryString      = "DELETE FROM $table WHERE {$readyCompareData['placeholderString']}";
            $pdoStatement     = $this->connection->prepare($queryString);
            $pdoStatement->execute($readyCompareData['values']);
        } catch (DataBaseException $e){
            echo $e;
        }
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