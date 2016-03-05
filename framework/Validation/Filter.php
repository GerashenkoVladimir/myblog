<?php

namespace Framework\Validation;

abstract class Filter
{
    abstract public function validate($param, $paramName, Validator $validator);
}