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

use App\Entity\Group;
use Doctrine\Common\Collections\ArrayCollection;
use Doyo\UserBundle\Model\User as BaseUser;
use Doyo\UserBundle\Model\UserInterface;
use Doyo\UserBundle\Test\MutableSpecTrait;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyCoreUserInterface;

class User extends BaseUser
{

}

class UserSpec extends ObjectBehavior
{
    use MutableSpecTrait;

    function let()
    {
        $this->beAnInstanceOf(User::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
        $this->shouldImplement(UserInterface::class);
        $this->shouldImplement(SymfonyCoreUserInterface::class);
    }

    public function getMutableProperties()
    {
        $group = new Group('test', ['ROLE_TEST']);
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
            'groups' => [
                'default' => ArrayCollection::class,
                'value' => $group
            ],
        ];
    }

    public function getMutableClassToTest()
    {
        return User::class;
    }

    public function it_should_add_role_to_user()
    {
        $this->getRoles()->shouldContain('ROLE_USER');
        $this->setRoles(['ROLE_FOO', 'ROLE_BAR'])->shouldReturn($this);
        $this->hasRole('ROLE_FOO')->shouldReturn(true);
        $this->hasRole('ROLE_BAR')->shouldReturn(true);
    }

    public function its_eraseCredentials_should_set_plainPassword_to_null()
    {
        $this->setPlainPassword('foo');
        $this->getPlainPassword()->shouldReturn('foo');
        $this->eraseCredentials();

        $this->getPlainPassword()->shouldReturn(null);
    }

    function it_should_check_group_by_name()
    {
        $group = new Group('test');
        $this->addGroup($group);
        $this->hasGroup('test')->shouldReturn(true);
    }
}
