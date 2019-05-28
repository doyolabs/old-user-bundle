<?php

namespace spec\Doyo\UserBundle\Manager;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doyo\UserBundle\Manager\UserManager;
use Doyo\UserBundle\Model\UserInterface;
use Doyo\UserBundle\Util\CanonicalFieldsUpdaterInterface;
use Doyo\UserBundle\Util\PasswordUpdaterInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserManagerSpec extends ObjectBehavior
{
    function let(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        ObjectRepository $objectRepository,
        ObjectManager $objectManager
    )
    {
        $objectManager->getRepository(Argument::any())
            ->willReturn($objectRepository);

        $this->beConstructedWith($passwordUpdater, $canonicalFieldsUpdater, $objectManager,  User::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserManager::class);
    }

    function its_create_should_create_new_user_object()
    {
        $this->create()->shouldBeAnInstanceOf(User::class);
    }

    function it_should_update_canonical_fields(
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        UserInterface $user
    )
    {
        $canonicalFieldsUpdater->updateCanonicalFields($user)
            ->shouldBeCalled();
        $this->updateCanonicalFields($user);
    }

    function it_should_update_password(
        PasswordUpdaterInterface $passwordUpdater,
        UserInterface $user
    )
    {
        $passwordUpdater->hashPassword($user)
            ->shouldBeCalled();

        $this->updatePassword($user);
    }

    function its_updateUser_should_update_canonical_fileds_and_password(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        ObjectManager $objectManager,
        UserInterface $user
    )
    {
        $passwordUpdater->hashPassword($user)->shouldBeCalled();
        $canonicalFieldsUpdater->updateCanonicalFields($user)->shouldBeCalled();
        $objectManager->persist($user)->shouldBeCalledTimes(2);
        $objectManager->flush()->shouldBeCalledOnce();

        $this->updateUser($user, false);
        $this->updateUser($user);
    }

    function its_findByUsername_should_find_user_by_username(
        ObjectRepository $objectRepository,
        UserInterface $user
    )
    {
        $objectRepository->findOneBy(['username'=>'foo'])
            ->shouldBeCalled()
            ->willReturn($user)
        ;

        $this->findByUserName('foo')->shouldReturn($user);
    }
}
