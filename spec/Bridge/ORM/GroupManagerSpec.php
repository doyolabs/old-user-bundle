<?php

namespace spec\Doyo\UserBundle\Bridge\ORM;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doyo\UserBundle\Model\GroupInterface;
use Doyo\UserBundle\Bridge\ORM\GroupManager;
use Doyo\UserBundle\Manager\GroupManagerInterface;
use PhpSpec\ObjectBehavior;
use spec\Doyo\UserBundle\Model\TestGroup;

class GroupManagerSpec extends ObjectBehavior
{
    function let(
        ObjectManager $objectManager,
        ObjectRepository $objectRepository
    )
    {
        $objectManager->getRepository(TestGroup::class)->willReturn($objectRepository);
        $this->beConstructedWith($objectManager, TestGroup::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GroupManager::class);
    }

    function it_should_be_a_group_manager()
    {
        $this->shouldImplement(GroupManagerInterface::class);
    }

    function it_should_delete_group(
        ObjectManager $objectManager,
        GroupInterface $group
    )
    {
        $objectManager->remove($group)
            ->shouldBeCalled();
        $objectManager->flush()->shouldBeCalled();

        $this->deleteGroup($group);
    }

    function it_should_find_group_by_criteria(
        ObjectRepository $objectRepository,
        GroupInterface $group
    )
    {
        $objectRepository->findOneBy(['name'=>'foo'])
            ->shouldBeCalled()->willReturn($group);

        $this->findGroupBy(['name' => 'foo']);
    }

    function it_should_find_all_groups(
        ObjectRepository $objectRepository
    )
    {
        $objectRepository->findAll()->shouldBeCalled()->willReturn([]);
        $this->findGroups();
    }

    function its_getClass_should_translate_shortname_entity(
        ObjectManager $objectManager,
        ClassMetadata $classMetadata
    )
    {
        $class = 'Foo:Bar';

        $objectManager->getClassMetadata($class)
            ->shouldBeCalled()
            ->willReturn($classMetadata);
        $classMetadata->getName()->shouldBeCalled()
            ->willReturn('SomeClass');

        $this->beConstructedWith($objectManager,$class);

        $this->getClass()->shouldReturn('SomeClass');
    }

    function it_should_update_group(
        ObjectManager $objectManager,
        GroupInterface $group
    )
    {
        $objectManager->persist($group)
            ->shouldBeCalled();
        $objectManager->flush()
            ->shouldBeCalled();

        $this->updateGroup($group);
    }
}
