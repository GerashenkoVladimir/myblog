<?php

namespace Framework\Validation\Filter;


use Framework\Validation\Filter;
use Framework\Validation\Validator;

class NotBlank extends Filter
{

    public function validate($param, $paramName, Validator $validator)
    {
        if (trim($param) == null) {
            $validator->setError($paramName, "Please fill this field!");
        }
    }
}