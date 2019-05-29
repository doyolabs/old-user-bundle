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
use Doyo\UserBundle\Util\PasswordUpdater;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class PasswordUpdaterSpec extends ObjectBehavior
{
    public function let(
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->beConstructedWith($encoderFactory);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(PasswordUpdater::class);
    }

    public function it_should_not_hash_password_when_user_plain_password_is_empty(
        EncoderFactoryInterface $encoderFactory,
        UserInterface $user
    ) {
        $user->getPlainPassword()->shouldBeCalled()->willReturn('');
        $encoderFactory->getEncoder($user)->shouldNotBeCalled();
        $this->hashPassword($user);
    }

    public function it_should_hash_user_password(
        EncoderFactoryInterface $encoderFactory,
        PasswordEncoderInterface $passwordEncoder,
        UserInterface $user
    ) {
        $user->getPlainPassword()->willReturn('password');
        $user->getSalt()->willReturn('salt');

        $encoderFactory->getEncoder($user)->shouldBeCalled()->willReturn($passwordEncoder);
        $user->setSalt(Argument::any())->shouldBeCalled();

        $passwordEncoder->encodePassword('password', 'salt')
            ->shouldBeCalled()
            ->willReturn('encoded-password');
        $user->setPassword('encoded-password')->shouldBeCalled();
        $user->eraseCredentials()->shouldBeCalled();

        $this->hashPassword($user);
    }

    public function it_should_not_use_salt_with_bcrypt_password_encoder(
        EncoderFactoryInterface $encoderFactory,
        BCryptPasswordEncoder $passwordEncoder,
        UserInterface $user
    ) {
        $user->getPlainPassword()->willReturn('password');
        $user->setSalt(null)->shouldBeCalled();
        $user->getSalt()->willReturn(null);

        $encoderFactory->getEncoder($user)->willReturn($passwordEncoder);
        $passwordEncoder
            ->encodePassword('password', null)
            ->shouldBeCalled()
            ->willReturn('hashed-password');

        $user->setPassword('hashed-password')->shouldBeCalled();
        $user->eraseCredentials()->shouldBeCalled();

        $this->hashPassword($user);
    }
}
