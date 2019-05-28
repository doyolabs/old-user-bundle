<?php

namespace spec\Doyo\UserBundle\Util;

use Doyo\UserBundle\Model\UserInterface;
use Doyo\UserBundle\Util\CanonicalFieldsUpdater;
use Doyo\UserBundle\Util\CanonicalizerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CanonicalFieldsUpdaterSpec extends ObjectBehavior
{
    function let(
        CanonicalizerInterface $usernameCanonicalizer,
        CanonicalizerInterface $emailCanonicalizer
    )
    {
        $this->beConstructedWith($usernameCanonicalizer, $emailCanonicalizer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CanonicalFieldsUpdater::class);
    }

    function it_should_update_user_canonical_fields(
        CanonicalizerInterface $usernameCanonicalizer,
        CanonicalizerInterface $emailCanonicalizer,
        UserInterface $user
    )
    {
        $usernameCanonicalizer->canonicalize('username')
            ->shouldBeCalled()
            ->willReturn('username-canonicalized');
        $emailCanonicalizer->canonicalize('email')
            ->shouldBeCalled()
            ->willReturn('email-canonicalized');

        $user->getUsername()->willReturn('username');
        $user->getEmail()->willReturn('email');

        $user->setUsernameCanonical('username-canonicalized')->shouldBeCalled();
        $user->setEmailCanonical('email-canonicalized')->shouldBeCalled();

        $this->updateCanonicalFields($user);
    }
}
