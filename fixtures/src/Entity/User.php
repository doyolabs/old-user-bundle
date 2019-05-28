<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doyo\UserBundle\Model\User as BaseUser;

/**
 * Class User
 *
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="sc_user")
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
}
