<?php

namespace Blog\Model;

use Framework\Model\ActiveRecord;
use Framework\Validation\Filter\Length;
use Framework\Validation\Filter\NotBlank;

/**
 * Class Post
 * @package Blog\Model
 */
class Post extends ActiveRecord
{
    /**
     * Post title
     *
     * @access public
     *
     * @var string
     */
    public $title;

    /**
     * Post content
     *
     * @access public
     *
     * @var string
     */
    public $content;

    /**
     * Post date
     *
     * @access public
     *
     * @var string
     */
    public $date;


    /**
     * Returns table name
     *
     * @access public
     *
     * @return string
     */
    public static function getTable()
    {
        return 'posts';
    }

    /**
     * Returns validation rulers
     *
     * @access public
     *
     * @return array
     */
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

    /**
     * Returns fields for saving
     *
     * @access public
     *
     * @return array
     */
    public static function getFieldsNames()
    {
        return array('title', 'content', 'date');
    }
}