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
}
