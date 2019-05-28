<?php

namespace spec\Doyo\UserBundle\Model;

use Doyo\UserBundle\Model\User;
use Doyo\UserBundle\Test\MutableSpecTrait;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserSpec extends ObjectBehavior
{
    use MutableSpecTrait;

    function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }

    public function getMutableProperties()
    {
        return [
            'username' => [],
            'usernameCanonical' => [],
            'email' => [],
            'emailCanonical' => [],
            'plainPassword' => [],
            'password' => [],
            'roles' => [
                'value' => ['ROLE_USER'],
                'default' => ['ROLE_USER'],
            ]
        ];
    }

    public function getMutableClassToTest()
    {
        return User::class;
    }


}
