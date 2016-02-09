<?php

namespace Blog\Model;

use Framework\Model\ActiveRecord;
use Framework\Validation\Filter\Length;
use Framework\Validation\Filter\NotBlank;
use Framework\Registry\Registry;

class Post extends ActiveRecord
{
    //private $title;
    public $content;
    public $date;

    private static $database;
    private static $registry;

    const ALL_POSTS = 'all';

    public static function getTable()
    {
        return 'posts';
    }

    public function getRules()
    {
        return array(
            'title'   => array(
                new NotBlank(),
                new Length(4, 100)
            ),
            'content' => array(new NotBlank())
        );
    }

    public static function find($record)
    {
        if (!isset(self::$registry)) {
            self::$registry = Registry::getInstance();
        }

        if (!isset(self::$database)) {
            self::$database = self::$registry['dataBase'];
        }

        if ($record == self::ALL_POSTS) {
            $allRecords = self::$database->selectAll(self::getTable());
            $posts = array();
            foreach ($allRecords as $rec) {
                $posts[] = new self($rec);
            }

            return $posts;
        } else {
            $record = self::$database->select(self::getTable(), array('*'), array('id' => $record));
            foreach ($record as $rec) {
                $post = new self($rec);
            }
            return $post;
        }
    }
}