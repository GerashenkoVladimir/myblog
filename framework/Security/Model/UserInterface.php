<?php

namespace Framework\Security\Model;

/**
 * Interface UserInterface
 *
 * @interface
 * @package Framework\Security\Model
 */
interface UserInterface
{
    /**
     * Find user by email
     *
     * @access public
     *
     * @param string $email
     *
     * @return mixed
     */
    public static function findByEmail($email);

    /**
     * Returns role
     *
     * @access public
     *
     * @return mixed
     */
    public function getRole();

}