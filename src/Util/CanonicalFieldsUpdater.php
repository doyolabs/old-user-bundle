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

namespace Doyo\UserBundle\Util;

use Doyo\UserBundle\Model\UserInterface;

class CanonicalFieldsUpdater implements CanonicalFieldsUpdaterInterface
{
    /**
     * @var CanonicalizerInterface
     */
    private $usernameCanonicalizer;

    /**
     * @var CanonicalizerInterface
     */
    private $emailCanonicalizer;

    /**
     * CanonicalFieldsUpdater constructor.
     */
    public function __construct(CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer)
    {
        $this->usernameCanonicalizer = $usernameCanonicalizer;
        $this->emailCanonicalizer    = $emailCanonicalizer;
    }

    public function updateCanonicalFields(UserInterface $user)
    {
        $user->setUsernameCanonical($this->usernameCanonicalizer->canonicalize($user->getUsername()));
        $user->setEmailCanonical($this->emailCanonicalizer->canonicalize($user->getEmail()));
    }

    /**
     * Canonicalizes an email.
     *
     * @param string|null $email
     *
     * @return string|null
     */
    public function canonicalizeEmail($email)
    {
        return $this->emailCanonicalizer->canonicalize($email);
    }

    /**
     * Canonicalizes a username.
     *
     * @param string|null $username
     *
     * @return string|null
     */
    public function canonicalizeUsername($username)
    {
        return $this->usernameCanonicalizer->canonicalize($username);
    }
}
