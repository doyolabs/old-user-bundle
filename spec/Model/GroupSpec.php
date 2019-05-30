<?php

namespace spec\Doyo\UserBundle\Model;

use Doyo\UserBundle\Model\Group;
use Doyo\UserBundle\Model\GroupInterface;
use Doyo\UserBundle\Test\MutableSpecTrait;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GroupSpec extends ObjectBehavior
{
    use MutableSpecTrait;

    function let()
    {
        $this->beAnInstanceOf(TestGroup::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Group::class);
    }

    function it_should_be_a_group()
    {
        $this->shouldImplement(GroupInterface::class);
    }

    public function getMutableProperties()
    {
        return [
            'id' => ['default' => null],
            'name' => ['default' => 'some-group'],
            'roles' => ['default' => ['ROLE_USER']]
        ];
    }

    public function getMutableClassToTest()
    {
        return Group::class;
    }


}
