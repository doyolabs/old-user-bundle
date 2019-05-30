<?php

namespace spec\Doyo\UserBundle\Bridge\ORM;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Doyo\UserBundle\Bridge\ORM\UserListener;
use Doyo\UserBundle\Model\UserInterface;
use Doyo\UserBundle\Util\CanonicalFieldsUpdaterInterface;
use Doyo\UserBundle\Util\PasswordUpdaterInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UserListenerSpec extends ObjectBehavior
{
    function let(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater
    )
    {
        $this->beConstructedWith($passwordUpdater, $canonicalFieldsUpdater);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserListener::class);
    }

    function it_should_be_a_doctrine_event_subscriber()
    {
        $this->shouldImplement(EventSubscriber::class);
    }

    function it_should_subscribe_to_pre_persist_event()
    {
        $this->getSubscribedEvents()->shouldContain(Events::prePersist);
    }

    function it_should_subscribe_to_pre_update_event()
    {
        $this->getSubscribedEvents()->shouldContain(Events::preUpdate);
    }

    function it_should_recompute_change_set_on_preUpate_event(
        LifecycleEventArgs $args,
        UserInterface $user,
        EntityManagerInterface $entityManager,
        UnitOfWork $unitOfWork,
        ClassMetadata $classMetadata
    )
    {
        $args->getObject()->willReturn($user);
        $args->getObjectManager()->willReturn($entityManager);
        $entityManager->getUnitOfWork()->willReturn($unitOfWork);
        $entityManager->getClassMetadata(Argument::any())->willReturn($classMetadata);
        $unitOfWork->recomputeSingleEntityChangeSet($classMetadata, $user)
            ->shouldBeCalled();

        $this->preUpdate($args);
    }

    function it_should_hash_password_on_doctrine_events(
        PasswordUpdaterInterface $passwordUpdater,
        LifecycleEventArgs $args,
        ObjectManager $objectManager,
        UserInterface $user
    )
    {
        $args->getObject()->willReturn($user);
        $args->getObjectManager()->willReturn($objectManager);

        $passwordUpdater->hashPassword($user)->shouldBeCalledTimes(2);

        $this->prePersist($args);
        $this->preUpdate($args);
    }

    function it_should_update_canonical_fields_on_doctrine_events(
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        LifecycleEventArgs $args,
        ObjectManager $objectManager,
        UserInterface $user
    )
    {
        $args->getObject()->willReturn($user);
        $args->getObjectManager()->willReturn($objectManager);

        $canonicalFieldsUpdater->updateCanonicalFields($user)->shouldBeCalledTimes(2);

        $this->prePersist($args);
        $this->preUpdate($args);
    }
}
