<?php

namespace Framework\Validation;

interface FilterInterface
{
    public function validate($param, $paramName, Validator $validator);
}