<?php

namespace Doyo\UserBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface as BaseInterface;

interface UserInterface extends BaseInterface
{
    /**
     * @param string|null $salt
     * @return static
     */
    public function setSalt(?string $salt);

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
     * @param array|null $roles
     * @return static
     */
    public function setRoles(?array $roles);

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