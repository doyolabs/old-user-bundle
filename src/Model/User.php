<?php

namespace Doyo\UserBundle\Model;

class User implements UserInterface
{
    /**
     * @var null|string
     */
    protected $username;

    /**
     * @var null|string
     */
    protected $usernameCanonical;

    /**
     * @var null|string
     */
    protected $email;

    /**
     * @var null|string
     */
    protected $emailCanonical;

    /**
     * @var null|array
     */
    protected $roles;

    /**
     * @var null|string
     */
    protected $plainPassword;

    /**
     * @var null|string
     */
    protected $password;

    /**
     * @var null|string
     */
    protected $salt;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @param string|null $salt
     * @return User
     */
    public function setSalt(?string $salt): User
    {
        $this->salt = $salt;
        return $this;
    }

    public function eraseCredentials()
    {

    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     * @return User
     */
    public function setUsername(?string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsernameCanonical(): ?string
    {
        return $this->usernameCanonical;
    }

    /**
     * @param string|null $usernameCanonical
     * @return User
     */
    public function setUsernameCanonical(?string $usernameCanonical): User
    {
        $this->usernameCanonical = $usernameCanonical;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return User
     */
    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmailCanonical(): ?string
    {
        return $this->emailCanonical;
    }

    /**
     * @param string|null $emailCanonical
     * @return User
     */
    public function setEmailCanonical(?string $emailCanonical): User
    {
        $this->emailCanonical = $emailCanonical;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * @param array|null $roles
     * @return User
     */
    public function setRoles(?array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     * @return User
     */
    public function setPlainPassword(?string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return User
     */
    public function setPassword(?string $password): User
    {
        $this->password = $password;
        return $this;
    }
}
