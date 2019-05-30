<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Doyo\UserBundle\Model;

/**
 * @author Anthonius Munthi <me@itstoni.com>
 */
interface GroupableInterface
{
    /**
     * Gets the groups granted to the user.
     *
     * @return \Traversable
     */
    public function getGroups();

    /**
     * Gets the name of the groups which includes the user.
     *
     * @return array
     */
    public function getGroupNames();

    /**
     * Indicates whether the user belongs to the specified group or not.
     *
     * @param string|GroupInterface $group Group object or name of the group
     *
     * @return bool
     */
    public function hasGroup($group);

    /**
     * Add a group to the user groups.
     *
     * @param GroupInterface $group
     *
     * @return static
     */
    public function addGroup(GroupInterface $group);

    /**
     * Remove a group from the user groups.
     *
     * @param GroupInterface $group
     *
     * @return static
     */
    public function removeGroup(GroupInterface $group);
}
