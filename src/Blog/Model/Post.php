<?php

namespace Blog\Model;

use Framework\Model\ActiveRecord;
use Framework\Validation\Filter\Length;
use Framework\Validation\Filter\NotBlank;

class Post extends ActiveRecord
{
    public $title;
    public $content;
    public $date;


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


    public function save()
    {
        self::$database->insert(self::getTable(),array(
            'title' => $this->title,
            'content' => $this->content,
            'date' => $this->date
        ));
    }

    public static function getFieldsNames()
    {
        return array('title', 'content', 'date');
    }
}