<?php


namespace Doyo\UserBundle\Manager;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doyo\UserBundle\Model\UserInterface;
use Doyo\UserBundle\Util\CanonicalFieldsUpdaterInterface;
use Doyo\UserBundle\Util\PasswordUpdaterInterface;

class UserManager
{
    /**
     * @var PasswordUpdaterInterface
     */
    private $passwordUpdater;

    /**
     * @var CanonicalFieldsUpdaterInterface
     */
    private $canonicalFieldsUpdater;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var string
     */
    private $class;

    /**
     * UserManager constructor.
     *
     * @param PasswordUpdaterInterface          $passwordUpdater
     * @param CanonicalFieldsUpdaterInterface   $canonicalFieldsUpdater
     * @param ObjectManager                     $manager
     * @param string                            $class
     */
    public function __construct(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdaterInterface $canonicalFieldsUpdater,
        ObjectManager $manager,
        $class
    )
    {
        $this->passwordUpdater = $passwordUpdater;
        $this->canonicalFieldsUpdater = $canonicalFieldsUpdater;
        $this->objectManager = $manager;
        $this->class = $class;
    }

    /**
     * Creates new user
     * @return UserInterface
     */
    public function create()
    {
        return new $this->class();
    }

    /**
     * @param string $username
     * @return object|null
     */
    public function findByUsername($username)
    {
        $repository = $this->getRepository();
        return $repository->findOneBy(['username' => $username]);
    }

    /**
     * @param UserInterface $user
     * @return static
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        $this->canonicalFieldsUpdater->updateCanonicalFields($user);

        return $this;
    }

    /**
     * @param UserInterface $user
     * @return static
     */
    public function updatePassword(UserInterface $user)
    {
        $this->passwordUpdater->hashPassword($user);

        return $this;
    }

    public function updateUser(UserInterface $user, $andFlush = true)
    {
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        $this->objectManager->persist($user);

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->objectManager->getRepository($this->class);
    }
}