<?php

/*
 * This file is part of the DoyoUserBundle project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
        return $this->findGroupBy(['name' => $name]);
    }
}
