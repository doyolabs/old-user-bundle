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

namespace Doyo\UserBundle\Manager;

use Doyo\UserBundle\Model\UserInterface;
use Doyo\UserBundle\Util\CanonicalFieldsUpdaterInterface;
use Doyo\UserBundle\Util\PasswordUpdaterInterface;

/**
 * Abstract User Manager implementation which can be used as base class for your
 * concrete manager.
 *
 * This class is taken from friendsofsymfony/user-bundle
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Anthonius Munthi <me@itstoni.com>
 */
abstract class AbstractUserManager implements UserManagerInterface
{
    private $passwordUpdater;
    private $canonicalFieldsUpdater;

    public function __construct(PasswordUpdaterInterface $passwordUpdater, CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater)
    {
        $this->passwordUpdater        = $passwordUpdater;
        $this->canonicalFieldsUpdater = $canonicalFieldsUpdater;
    }

    /**
     * {@inheritdoc}
     */
    public function createUser()
    {
        $class = $this->getClass();

        return new $class();
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByEmail($email)
    {
        return $this->findUserBy(['emailCanonical' => $this->canonicalFieldsUpdater->canonicalizeEmail($email)]);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsername($username)
    {
        return $this->findUserBy(['usernameCanonical' => $this->canonicalFieldsUpdater->canonicalizeUsername($username)]);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
            $user = $this->findUserByEmail($usernameOrEmail);
            if (null !== $user) {
                return $user;
            }
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByConfirmationToken($token)
    {
        return $this->findUserBy(['confirmationToken' => $token]);
    }

    /**
     * {@inheritdoc}
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        $this->canonicalFieldsUpdater->updateCanonicalFields($user);
    }

    /**
     * {@inheritdoc}
     */
    public function updatePassword(UserInterface $user)
    {
        $this->passwordUpdater->hashPassword($user);
    }
}
