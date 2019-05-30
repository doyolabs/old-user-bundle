<?php

namespace spec\Doyo\UserBundle\Manager;

use Doyo\UserBundle\Manager\GroupManager as BaseGroupManager;
use Doyo\UserBundle\Manager\GroupManagerInterface;
use Doyo\UserBundle\Model\GroupInterface;
use PhpSpec\ObjectBehavior;
use spec\Doyo\UserBundle\Model\TestGroup;

class GroupManager extends BaseGroupManager
{
    public function deleteGroup(GroupInterface $group)
    {
        // TODO: Implement deleteGroup() method.
    }

    public function findGroupBy(array $criteria)
    {
        return new TestGroup();
    }

    public function findGroups()
    {
        // TODO: Implement findGroups() method.
    }

    public function getClass()
    {
        return TestGroup::class;
    }

    public function updateGroup(GroupInterface $group)
    {
        // TODO: Implement updateGroup() method.
    }
}

class GroupManagerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf(GroupManager::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GroupManager::class);
    }

    function it_should_be_a_group_manager()
    {
        $this->shouldImplement(GroupManagerInterface::class);
    }

    function it_should_create_new_group()
    {
        $this->createGroup('some-name')
            ->shouldHaveType(TestGroup::class);
    }

    function it_should_find_group_by_name()
    {
        $this->findGroupByName('some-name')
            ->shouldHaveType(TestGroup::class);
    }
}
