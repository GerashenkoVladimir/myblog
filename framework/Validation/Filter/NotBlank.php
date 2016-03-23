<?php

namespace Framework\Validation\Filter;


use Framework\Validation\FilterInterface;
use Framework\Validation\Validator;

/**
 * Class NotBlank
 * @package Framework\Validation\Filter
 */
class NotBlank implements  FilterInterface
{

    /**
     * NotBlank validation
     *
     * @access public
     *
     * @param string    $param
     * @param string    $paramName
     * @param Validator $validator
     *
     * @return void
     */
    public function validate($param, $paramName, Validator $validator)
    {
        if (trim($param) == null) {
            $validator->setError($paramName, "Please fill this field!");
        }
    }
}