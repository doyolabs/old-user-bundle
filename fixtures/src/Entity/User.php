<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doyo\UserBundle\Model\User as BaseUser;

/**
 * Class User
 *
 * @package App\Entity
 *
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @var string
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var null|string
     */
    protected $fullName;

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param string|null $fullName
     * @return User
     */
    public function setFullName(?string $fullName): User
    {
        $this->fullName = $fullName;
        return $this;
    }
}
