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
     * @var bool
     */
    protected $enabled;

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
        $this->enabled = false;
    }

    /**
     * @param  string   $role
     * @return static
     */
    public function addRole($role)
    {
        $role = strtoupper($role);

        if(!$this->hasRole($role)){
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param string $role
     * @return bool
     */
    public function hasRole($role):bool
    {
        $role = strtoupper($role);
        return in_array($role, $this->roles, true);
    }

    /**
     * @param   string $role
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
     * @param array|null $roles
     * @return static
     */
    public function setRoles(?array $roles)
    {
        $this->roles = [];
        foreach($roles as $role){
            $this->addRole($role);
        }

        return $this;
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
     * @return static
     */
    public function setSalt(?string $salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return static
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;

        return $this;
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
     * @return static
     */
    public function setUsername(?string $username)
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
     * @return static
     */
    public function setUsernameCanonical(?string $usernameCanonical)
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
     * @return static
     */
    public function setEmail(?string $email)
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
     * @return static
     */
    public function setEmailCanonical(?string $emailCanonical)
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
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     * @return static
     */
    public function setPlainPassword(?string $plainPassword)
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
     * @return static
     */
    public function setPassword(?string $password)
    {
        $this->password = $password;
        return $this;
    }
}
