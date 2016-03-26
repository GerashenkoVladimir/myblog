<?php

namespace Framework\Validation\Filter;

use Framework\Validation\FilterInterface;
use Framework\Validation\Validator;

/**
 * Class Length
 * @package Framework\Validation\Filter
 */
class Length implements FilterInterface
{
    /**
     * Min length
     *
     * @access private
     *
     * @var int
     */
    private $minLength;

    /**
     * Max length
     *
     * @access private
     *
     * @var int
     */
    private $maxLength;

    /**
     * Length constructor
     *
     * @access public
     *
     * @param int $minLength
     * @param int $maxLength
     */
    public function __construct($minLength, $maxLength)
    {
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }


    /**
     * Length validation
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
        $strLength = strlen($param);
        if ($strLength < $this->minLength) {
            $validator->setError($paramName, "Enter more than $this->minLength characters!");
        } elseif ($strLength > $this->maxLength) {
            $validator->setError($paramName, "Enter less than $this->maxLength characters!");
        }
    }
}