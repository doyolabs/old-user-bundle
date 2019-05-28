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

namespace Doyo\UserBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface as BaseInterface;

interface UserInterface extends BaseInterface
{
    public const ROLE_DEFAULT = 'ROLE_USER';

    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @return null|string|int
     */
    public function getId();

    /**
     * @param string $role
     *
     * @return static
     */
    public function addRole($role);

    /**
     * @param string $role
     * @return bool
     */
    public function hasRole($role): bool;

    /**
     * @param string $role
     *
     * @return static
     */
    public function removeRole($role);

    /**
     * @return static
     */
    public function setRoles(?array $roles);

    /**
     * @return static
     */
    public function setSalt(?string $salt);

    public function isEnabled(): bool;

    /**
     * @return static
     */
    public function setEnabled(bool $enabled);

    /**
     * @return static
     */
    public function setUsername(?string $username);

    public function getUsernameCanonical(): ?string;

    /**
     * @return static
     */
    public function setUsernameCanonical(?string $usernameCanonical);

    public function getEmail(): ?string;

    /**
     * @return static
     */
    public function setEmail(?string $email);

    public function getEmailCanonical(): ?string;

    /**
     * @return static
     */
    public function setEmailCanonical(?string $emailCanonical);

    public function getPlainPassword(): ?string;

    /**
     * @return static
     */
    public function setPlainPassword(?string $plainPassword);

    /**
     * @return static
     */
    public function setPassword(?string $password);

    /**
     * @return \DateTimeImmutable|null
     */
    public function getLastLogin(): ?\DateTimeImmutable;

    /**
     * @param \DateTimeImmutable|null $lastLogin
     * @return static
     */
    public function setLastLogin(?\DateTimeImmutable $lastLogin);

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string;

    /**
     * @param string|null $confirmationToken
     * @return static
     */
    public function setConfirmationToken(?string $confirmationToken);

    /**
     * @return \DateTimeImmutable|null
     */
    public function getPasswordRequestedAt(): ?\DateTimeImmutable;

    /**
     * @param \DateTimeImmutable|null $passwordRequestedAt
     * @return static
     */
    public function setPasswordRequestedAt(?\DateTimeImmutable $passwordRequestedAt);
}
