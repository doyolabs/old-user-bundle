<?php

namespace Doyo\UserBundle\Manager;

/**
 * Abstract Group Manager implementation which can be used as base class for your
 * concrete manager.
 *
 * @author Anthonius Munthi <me@itstoni.com>
 */
abstract class GroupManager implements GroupManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function createGroup($name)
    {
        $class = $this->getClass();

        return new $class($name);
    }

    /**
     * {@inheritdoc}
     */
    public function findGroupByName($name)
    {
        return $this->findGroupBy(array('name' => $name));
    }
}
