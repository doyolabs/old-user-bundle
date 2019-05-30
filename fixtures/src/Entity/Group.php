<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doyo\UserBundle\Model\Group as BaseGroup;

/**
 * Class Group
 *
 * @ORM\Entity()
 * @ORM\Table(name="sc_group")
 * @package App\Entity
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @var string
     */
    protected $id;
}