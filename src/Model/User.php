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

class User implements UserInterface
{
    /**
     * @var string|null
     */
    protected $username;

    /**
     * @var string|null
     */
    protected $usernameCanonical;

    /**
     * @var string|null
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $emailCanonical;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var array|null
     */
    protected $roles;

    /**
     * @var string|null
     */
    protected $plainPassword;

    /**
     * @var string|null
     */
    protected $password;

    /**
     * @var string|null
     */
    protected $salt;

    public function __construct()
    {
        $this->roles   = ['ROLE_USER'];
        $this->enabled = false;
    }

    /**
     * @param string $role
     *
     * @return static
     */
    public function addRole($role)
    {
        $role = strtoupper($role);

        if (!$this->hasRole($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param string $role
     */
    public function hasRole($role): bool
    {
        $role = strtoupper($role);

        return \in_array($role, $this->roles, true);
    }

    /**
     * @param string $role
     *
     * @return static
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @return static
     */
    public function setRoles(?array $roles)
    {
        $this->roles = [];
        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @return static
     */
    public function setSalt(?string $salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return static
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return static
     */
    public function setUsername(?string $username)
    {
        $this->username = $username;

        return $this;
    }

    public function getUsernameCanonical(): ?string
    {
        return $this->usernameCanonical;
    }

    /**
     * @return static
     */
    public function setUsernameCanonical(?string $usernameCanonical)
    {
        $this->usernameCanonical = $usernameCanonical;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return static
     */
    public function setEmail(?string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmailCanonical(): ?string
    {
        return $this->emailCanonical;
    }

    /**
     * @return static
     */
    public function setEmailCanonical(?string $emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @return static
     */
    public function setPlainPassword(?string $plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return static
     */
    public function setPassword(?string $password)
    {
        $this->password = $password;

        return $this;
    }
}
