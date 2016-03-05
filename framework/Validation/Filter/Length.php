<?php

namespace Framework\Validation\Filter;

use Framework\Validation\Filter;
use Framework\Validation\Validator;

class Length extends Filter
{
    private $minLength;

    private $maxLength;

    public function __construct($minLength, $maxLength)
    {
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }


    public function validate($param, $paramName, Validator $validator)
    {
        $strLength = strlen($param);
        if ($strLength < $this->minLength) {
            $validator->setError($paramName, "Enter more than $this->minLength characters!");
        } elseif ($strLength > $this->maxLength) {
            $validator->setError($paramName, "Enter less than $this->maxLength characters!");
        }
    }
}