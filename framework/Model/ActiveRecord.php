<?php
namespace Framework\Model;

use Framework\DataBase\DataBase;
use Framework\DI\Service;
use Framework\Sessions\Sessions;

abstract class ActiveRecord
{
    public $id;
    /**
     * @var DataBase
     */
    protected static $database;

    /**
     * @var Sessions
     */
    protected static $sessions;

    private $fields = array();

    const ALL_RECORDS = 'all';

    public function __construct($record = array())
    {
        foreach ($record as $r => $value){
            $this->$r = $value;
        }

        if (!isset(static::$database)) {
            self::$database = Service::get('dataBase');
        }

        $this->fields = static::getFieldsNames();
    }

    abstract public static function getTable();

    abstract public static function getFieldsNames();

    abstract public function getRules();

    public function save()
    {
        self::$database->insert(static::getTable(), static::prepareParams());
    }

    public function update($param, $value)
    {
        self::$database->update(static::getTable(), static::prepareParams(), array($param => $value));
    }

    public function delete($param, $value)
    {
        self::$database->delete(static::getTable(),array($param => $value));
    }

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

    private function prepareParams()
    {
        $preparedParams = array();
        foreach ($this->fields as $field) {
            $preparedParams[$field] = $this->$field;
        }

        return $preparedParams;
    }
}