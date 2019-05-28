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
}