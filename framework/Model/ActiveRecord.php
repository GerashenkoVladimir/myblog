<?php
namespace Framework\Model;

use Framework\DataBase\DataBase;
use Framework\DI\Service;

abstract class ActiveRecord
{
    protected static $registry;
    /**
     * @var DataBase
     */
    protected static $database;
    protected static $sessions;

    const ALL_RECORDS = 'all';

    public function __construct($record = array())
    {
        foreach ($record as $r => $value){
            $this->$r = $value;
        }
        if (!isset(static::$database)) {
            self::$database = Service::get('dataBase');
        }
    }

    abstract public static function getTable();

    abstract public function save();

    abstract public function getRules();

    abstract public static function getFieldsNames();



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
}