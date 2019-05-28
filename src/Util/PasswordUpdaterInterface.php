<?php


namespace Doyo\UserBundle\Util;


use Doyo\UserBundle\Model\UserInterface;

interface PasswordUpdaterInterface
{
    /**
     * @param UserInterface $user
     * @return static
     */
    public function hashPassword(UserInterface $user);
}