<?php

namespace spec\Doyo\UserBundle\Bridge\ORM;

use App\Entity\User;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doyo\UserBundle\Bridge\ORM\UserManager;
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

    function its_deleteUser_should_delete_user(
        ObjectManager $objectManager,
        UserInterface $user
    )
    {
        $objectManager->remove($user)->shouldBeCalled();
        $objectManager->flush()->shouldBeCalled();

        $this->deleteUser($user);
    }

    function its_getClass_should_get_user_class(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        ObjectManager $objectManager,
        ClassMetadata $classMetadata
    )
    {
        $objectManager->getClassMetadata('Foo:Bar')
            ->shouldBeCalled()
            ->willReturn($classMetadata)
        ;

        $classMetadata->getName()
            ->shouldBeCalled()
            ->willReturn('foo-bar')
        ;

        $this->beConstructedWith(
            $passwordUpdater,
            $canonicalFieldsUpdater,
            $objectManager,
            'Foo:Bar'
        );

        $this->getClass()->shouldReturn('foo-bar');
    }

    function its_findUsers_should_get_all_users(
        ObjectRepository $objectRepository
    )
    {
        $objectRepository->findAll()->shouldBeCalled();
        $this->findUsers();
    }

    function its_reloadUser_should_refresh_user_object(
        ObjectManager $objectManager,
        UserInterface $user
    )
    {
        $objectManager->refresh($user)->shouldBeCalled();
        $this->reloadUser($user);
    }

    function its_create_should_create_new_user_object()
    {
        $this->createUser()->shouldBeAnInstanceOf(User::class);
    }

    function its_findUserByEmail_should_find_user_by_email(
        ObjectRepository $objectRepository,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        UserInterface $user
    )
    {
        $canonicalFieldsUpdater->canonicalizeEmail('foo')
            ->shouldBeCalled()
            ->willReturn('foo-canonical');

        $objectRepository->findOneBy(['emailCanonical' => 'foo-canonical'])
            ->shouldBeCalled()
            ->willReturn($user);

        $this->findUserByEmail('foo');
    }

    function its_findUserByUsername_should_find_user_by_username(
        ObjectRepository $objectRepository,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        UserInterface $user
    )
    {
        $canonicalFieldsUpdater->canonicalizeUsername('foo')
            ->shouldBeCalled()
            ->willReturn('foo-canonical');

        $objectRepository->findOneBy(['usernameCanonical'=>'foo-canonical'])
            ->shouldBeCalled()
            ->willReturn($user)
        ;

        $this->findUserByUsername('foo')->shouldReturn($user);
    }

    function it_should_find_user_by_username_or_email(
        ObjectRepository $objectRepository,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        UserInterface $user
    )
    {
        $canonicalFieldsUpdater->canonicalizeEmail('foo@bar.com')
            ->shouldBeCalled()
            ->willReturn('email-canonical');
        $canonicalFieldsUpdater->canonicalizeUsername('foo')
            ->shouldBeCalled()
            ->willReturn('username-canonical');

        $objectRepository->findOneBy(['usernameCanonical' => 'username-canonical'])
            ->shouldBeCalled()
            ->willReturn($user);
        $objectRepository->findOneBy(['emailCanonical' => 'email-canonical'])
            ->shouldBeCalled()
            ->willReturn($user);


        $this->findUserByUsernameOrEmail('foo')->shouldReturn($user);
        $this->findUserByUsernameOrEmail('foo@bar.com')->shouldReturn($user);
    }

    function it_should_find_user_by_confirmation_token(
        ObjectRepository $objectRepository,
        UserInterface $user
    )
    {
        $objectRepository->findOneBy(['confirmationToken' => 'token'])
            ->shouldBeCalled()
            ->willReturn($user);

        $this->findUserByConfirmationToken('token')->shouldReturn($user);
    }

    function it_should_update_canonical_fields(
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        UserInterface $user
    )
    {
        $canonicalFieldsUpdater
            ->updateCanonicalFields($user)
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
}
