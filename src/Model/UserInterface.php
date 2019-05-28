<?php

namespace Doyo\UserBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface as BaseInterface;

interface UserInterface extends BaseInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';

    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @param string $role
     * @return static
     */
    public function addRole($role);

    /**
     * @param   string $role
     * @return  bool
     */
    public function hasRole($role):bool;

    /**
     * @param string $role
     * @return static
     */
    public function removeRole($role);

    /**
     * @param array|null $roles
     * @return static
     */
    public function setRoles(?array $roles);

    /**
     * @param string|null $salt
     * @return static
     */
    public function setSalt(?string $salt);

    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @param bool $enabled
     * @return static
     */
    public function setEnabled(bool $enabled);

    /**
     * @param string|null $username
     * @return static
     */
    public function setUsername(?string $username);

    /**
     * @return string|null
     */
    public function getUsernameCanonical(): ?string;

    /**
     * @param string|null $usernameCanonical
     * @return static
     */
    public function setUsernameCanonical(?string $usernameCanonical);

    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @param string|null $email
     * @return static
     */
    public function setEmail(?string $email);

    /**
     * @return string|null
     */
    public function getEmailCanonical(): ?string;

    /**
     * @param string|null $emailCanonical
     * @return static
     */
    public function setEmailCanonical(?string $emailCanonical);

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string;

    /**
     * @param string|null $plainPassword
     * @return static
     */
    public function setPlainPassword(?string $plainPassword);

    /**
     * @param string|null $password
     * @return static
     */
    public function setPassword(?string $password);
}
