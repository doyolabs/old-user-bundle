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

namespace Doyo\UserBundle\Bridge\ORM;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doyo\UserBundle\Model\UserInterface;
use Doyo\UserBundle\Util\CanonicalFieldsUpdaterInterface;
use Doyo\UserBundle\Util\PasswordUpdaterInterface;

class UserListener implements EventSubscriber
{
    /**
     * @var PasswordUpdaterInterface
     */
    private $passwordUpdater;

    /**
     * @var CanonicalFieldsUpdaterInterface
     */
    private $canonicalFieldsUpdater;

    /**
     * UserListener constructor.
     */
    public function __construct(PasswordUpdaterInterface $passwordUpdater, CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater)
    {
        $this->passwordUpdater        = $passwordUpdater;
        $this->canonicalFieldsUpdater = $canonicalFieldsUpdater;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * Pre persist listener based on doctrine common.
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof UserInterface) {
            $this->updateUserFields($object);
        }
    }

    /**
     * Pre update listener based on doctrine common.
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof UserInterface) {
            $this->updateUserFields($object);
            $this->recomputeChangeSet($args->getObjectManager(), $object);
        }
    }

    /**
     * Updates the user properties.
     */
    private function updateUserFields(UserInterface $user)
    {
        $this->canonicalFieldsUpdater->updateCanonicalFields($user);
        $this->passwordUpdater->hashPassword($user);
    }

    /**
     * Recomputes change set for Doctrine implementations not doing it automatically after the event.
     */
    private function recomputeChangeSet(ObjectManager $om, UserInterface $user)
    {
        $meta = $om->getClassMetadata(\get_class($user));

        if ($om instanceof EntityManagerInterface) {
            $om->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $user);

            return;
        }

        if ($om instanceof DocumentManager) {
            $om->getUnitOfWork()->recomputeSingleDocumentChangeSet($meta, $user);
        }
    }
}
