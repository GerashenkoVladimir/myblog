<?php

namespace Framework\Security\Model;

interface UserInterface
{
    public static function findByEmail($email);
    public static function getFieldsNames();


}