<?php


namespace Doyo\UserBundle\Util;


use Doyo\UserBundle\Model\UserInterface;

interface CanonicalFieldsUpdaterInterface
{
    /**
     * @param UserInterface $user
     * @return static
     */
    public function updateCanonicalFields(UserInterface $user);

    public function canonicalizeUsername($username);

    public function canonicalizeEmail($email);
}