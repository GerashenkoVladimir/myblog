<?php

namespace Framework\Validation;

use Framework\Model\ActiveRecord;

/**
 * Class Validator
 * @package Framework\Validation
 */
class Validator
{
    /**
     * ActiveRecord object
     *
     * @access private
     *
     * @var ActiveRecord
     */
    private $record;

    /**
     * Errors
     *
     * @access private
     *
     * @var array
     */
    private $errors = array();

    /**
     * Validator constructor
     *
     * @access public
     *
     * @param ActiveRecord $record
     */
    public function __construct(ActiveRecord $record)
    {
        $this->record = $record;
    }

    /**
     * Validate all rulers
     *
     * @access public
     *
     * @return bool
     */
    public function isValid()
    {
        $setOfRules = $this->record->getRules();
        foreach ($setOfRules as $var => $rules) {
            foreach ($rules as $rule) {
                $rule->validate($this->record->$var, $var, $this);
            }
        }

        return !empty($this->errors) ? false : true;
    }

    /**
     * Sets error
     *
     * @access public
     *
     * @param string $fieldName
     * @param string $error
     *
     * @return void
     */
    public function setError($fieldName, $error)
    {
        $this->errors[$fieldName] = isset($this->errors[$fieldName]) ? $this->errors[$fieldName] . '<br>' . $error : $error;
    }

    /**
     * Returns all errors
     *
     * @access public
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

}