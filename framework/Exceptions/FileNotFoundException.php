<?php

namespace Framework\Exceptions;

class FileNotFoundException extends \Exception
{
    public function __construct($filePath)
    {
        parent::__construct($filePath);
    }

    public function __toString()
    {
        return "File $this->message not found!!!";
    }
}