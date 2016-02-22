<?php

namespace Framework\Security\Model;

interface UserInterface
{
    public function findByEmail($email);
    //public function setUser();
}