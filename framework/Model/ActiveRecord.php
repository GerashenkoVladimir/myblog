<?php
namespace Framework\Model;



abstract class ActiveRecord
{
    public function __construct($record = array())
    {
        foreach ($record as $r => $value){
            $this->$r = $value;
        }
    }
}