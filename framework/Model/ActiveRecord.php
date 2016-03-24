<?php
namespace Framework\Model;

use Framework\DataBase\DataBase;
use Framework\DI\Service;
use Framework\Sessions\Sessions;

/**
 * Class ActiveRecord
 *
 * @package Framework\Model
 * @abstract
 */
abstract class ActiveRecord
{
    /**
     * Database instance
     *
     * @access protected
     * @static
     *
     * @var DataBase
     */
    protected static $database;

    /**
     * Sessions instance
     *
     * @access protected
     * @static
     *
     * @var Sessions
     */
    protected static $sessions;

    /**
     * Fields of database table
     *
     * @access private
     *
     * @var array
     */
    private $fields = array();

    /**
     * @const
     * @var string
     */
    const ALL_RECORDS = 'all';

    /**
     * ActiveRecord constructor
     *
     * @param array $record
     *
     * @throws \Framework\Exception\ServiceException
     */
    public function __construct($record = array())
    {
        foreach ($record as $r => $value) {
            $this->$r = $value;
        }

        if (!isset(static::$database)) {
            self::$database = Service::get('dataBase');
        }

        $this->fields = static::getFieldsNames();
    }


    /**
     * Returns table name. It must be overridden in the derived class
     *
     * @access public
     * @static
     *
     * @return string
     */
    public static function getTable()
    {
        return "";
    }

    /**
     * Returns fields of table. It must be overridden in the derived class
     *
     * @access public
     * @static
     *
     * @return mixed
     */
    public static function getFieldsNames()
    {
        return array();
    }

    /**
     * Returns validation rules. It must be overridden in the derived class
     *
     * @access public
     *
     * @return array
     */
    public function getRules()
    {
        return array();
    }

    /**
     * Saves data in database
     *
     * @access public
     *
     * @return void
     * @throws \Framework\Exception\DataBaseException
     */
    public function save()
    {
        self::$database->insert(static::getTable(), static::prepareParams());
    }

    /**
     * Updates data in database
     *
     * @access public
     *
     * @param string $comparedKey
     * @param string $comparedValue
     *
     * @return void
     * @throws \Framework\Exception\DataBaseException
     */
    public function update($comparedKey, $comparedValue)
    {
        self::$database->update(static::getTable(), static::prepareParams(), array($comparedKey => $comparedValue));
    }

    /**
     * Deletes data from database
     *
     * @access public
     *
     * @param string $compareKey
     * @param string $comparedValue
     *
     * @return void
     * @throws \Framework\Exception\DataBaseException
     */
    public function delete($compareKey, $comparedValue)
    {
        self::$database->delete(static::getTable(), array($compareKey => $comparedValue));
    }

    /**
     * Find and return records from database by param
     *
     * @access public
     * @static
     *
     * @param string $record
     *
     * @return array|ActiveRecord
     * @throws \Framework\Exception\DataBaseException
     * @throws \Framework\Exception\ServiceException
     */
    public static function find($record)
    {
        if (!isset(static::$database)) {
            self::$database = Service::get('dataBase');
        }

        if ($record == static::ALL_RECORDS) {
            $allRecords = self::$database->selectAll(static::getTable());
            $posts = array();
            foreach ($allRecords as $rec) {
                $posts[] = new static($rec);
            }

            return $posts;
        } else {
            $record = self::$database->select(static::getTable(), array('*'), array('id' => $record));

            return new static($record);
        }
    }

    /**
     * Prepares params for saving
     *
     * @access private
     *
     * @return array
     */
    private function prepareParams()
    {
        $preparedParams = array();
        foreach ($this->fields as $field) {
            $preparedParams[$field] = $this->$field;
        }

        return $preparedParams;
    }
}