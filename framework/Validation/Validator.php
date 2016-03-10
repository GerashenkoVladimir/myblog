<?php

namespace Framework\Validation;

use Framework\Model\ActiveRecord;

class Validator
{
    private $record;

    private $errors = array();

    public function __construct(ActiveRecord $record)
    {
        $this->record = $record;
    }

    public function isValid()
    {
        $setOfRules = $this->record->getRules();
        foreach ($setOfRules as $var => $rules) {
            foreach ($rules as $rule) {
                $rule->validate($this->record->$var, $var, $this);
            }
        }

        return !empty($this->errors)?false:true;
    }

    public function setError($fieldName, $error)
    {
        $this->errors[$fieldName] = isset($this->errors[$fieldName])?$this->errors[$fieldName].'<br>'.$error:$error;
    }

    public function getErrors()
    {
        return $this->errors;
    }

}