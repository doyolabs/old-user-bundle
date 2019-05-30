<?php

namespace Doyo\UserBundle\Bridge\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doyo\UserBundle\Manager\GroupManager as BaseGroupManager;
use Doyo\UserBundle\Model\GroupInterface;

class GroupManager extends BaseGroupManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var string
     */
    private $class;

    public function __construct(
        ObjectManager $objectManager,
        $class
    )
    {
        $this->objectManager = $objectManager;
        $this->class = $class;
    }

    public function deleteGroup(GroupInterface $group)
    {
        $this->objectManager->remove($group);
        $this->objectManager->flush();
    }

    public function findGroupBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    public function findGroups()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        if (false !== strpos($this->class, ':')) {
            $metadata = $this->objectManager->getClassMetadata($this->class);
            $this->class = $metadata->getName();
        }

        return $this->class;
    }

    public function updateGroup(GroupInterface $group, $andFlush = true)
    {
        $this->objectManager->persist($group);
        if($andFlush){
            $this->objectManager->flush();
        }
    }

    public function getRepository()
    {
        return $this->objectManager->getRepository($this->getClass());
    }
}
