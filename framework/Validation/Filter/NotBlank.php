<?php

namespace Framework\Validation\Filter;


use Framework\Validation\FilterInterface;
use Framework\Validation\Validator;

class NotBlank implements  FilterInterface
{

    public function validate($param, $paramName, Validator $validator)
    {
        if (trim($param) == null) {
            $validator->setError($paramName, "Please fill this field!");
        }
    }
}