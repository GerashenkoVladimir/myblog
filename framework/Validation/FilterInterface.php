<?php

namespace Framework\Validation;

/**
 * Interface FilterInterface
 * @package Framework\Validation
 * @interface
 */
interface FilterInterface
{
    /**
     * Validate fields
     *
     * @access public
     *
     * @param string    $param
     * @param string    $paramName
     * @param Validator $validator
     *
     * @return void
     */
    public function validate($param, $paramName, Validator $validator);
}