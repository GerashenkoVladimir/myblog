<?php

namespace Framework\Security\Model;

interface UserInterface
{
    public function isAuthenticated();
    public static function findByEmail($email);
}