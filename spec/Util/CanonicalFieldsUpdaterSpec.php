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

namespace spec\Doyo\UserBundle\Util;

use Doyo\UserBundle\Model\UserInterface;
use Doyo\UserBundle\Util\CanonicalFieldsUpdater;
use Doyo\UserBundle\Util\CanonicalizerInterface;
use PhpSpec\ObjectBehavior;

class CanonicalFieldsUpdaterSpec extends ObjectBehavior
{
    public function let(
        CanonicalizerInterface $usernameCanonicalizer,
        CanonicalizerInterface $emailCanonicalizer
    ) {
        $this->beConstructedWith($usernameCanonicalizer, $emailCanonicalizer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CanonicalFieldsUpdater::class);
    }

    public function it_should_update_user_canonical_fields(
        CanonicalizerInterface $usernameCanonicalizer,
        CanonicalizerInterface $emailCanonicalizer,
        UserInterface $user
    ) {
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
