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

namespace spec\Doyo\UserBundle\Model;

use Doyo\UserBundle\Model\User;
use Doyo\UserBundle\Model\UserInterface;
use Doyo\UserBundle\Test\MutableSpecTrait;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyCoreUserInterface;

class UserSpec extends ObjectBehavior
{
    use MutableSpecTrait;

    public function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
        $this->shouldImplement(UserInterface::class);
        $this->shouldImplement(SymfonyCoreUserInterface::class);
    }

    public function getMutableProperties()
    {
        return [
            'id' => [
                'default' => null,
            ],
            'username'          => [],
            'usernameCanonical' => [],
            'email'             => [],
            'emailCanonical'    => [],
            'enabled'           => [
                'default' => false,
                'value'   => true,
            ],
            'salt'      => [],
            'password'  => [],
            'lastLogin' => [
                'value' => new \DateTimeImmutable(),
            ],
            'confirmationToken'   => [],
            'passwordRequestedAt' => [
                'value' => new \DateTimeImmutable(),
            ],
            'roles' => [
                'value'   => 'ROLE_ADMIN',
                'default' => ['ROLE_USER'],
            ],
            'plainPassword' => [],
        ];
    }

    public function getMutableClassToTest()
    {
        return User::class;
    }

    public function its_setRoles_should_use_add_role()
    {
        $this->getRoles()->shouldContain('ROLE_USER');
        $this->setRoles(['ROLE_FOO', 'ROLE_BAR'])->shouldReturn($this);
        $this->hasRole('ROLE_FOO')->shouldReturn(true);
        $this->hasRole('ROLE_BAR')->shouldReturn(true);
    }

    public function its_eraseCredentials_should_reset_credentials()
    {
        $this->setPlainPassword('foo');
        $this->getPlainPassword()->shouldReturn('foo');
        $this->eraseCredentials();

        $this->getPlainPassword()->shouldReturn(null);
    }
}
