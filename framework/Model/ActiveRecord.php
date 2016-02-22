<?php
namespace Framework\Model;



use Framework\Registry\Registry;

abstract class ActiveRecord
{
    protected static $registry;
    protected static $database;
    protected static $sessions;

    const ALL_RECORDS = 'all';

    public function __construct($record)
    {
        foreach ($record as $r => $value){
            $this->$r = $value;
        }
        if (!isset(static::$registry)) {
            self::$registry = Registry::getInstance();
        }


    }

    abstract static function getTable();



    public static function find($record)
    {
        if (!isset(static::$registry)) {
            self::$registry = Registry::getInstance();
        }

        if (!isset(static::$database)) {
            self::$database = self::$registry['dataBase'];
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
            foreach ($record as $rec) {
                $post = new static($rec);
            }
            return $post;
        }
    }
}