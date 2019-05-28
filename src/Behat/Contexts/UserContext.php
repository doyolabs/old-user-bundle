<?php


namespace Doyo\UserBundle\Behat\Contexts;


use Behat\Behat\Context\Context;
use Doyo\UserBundle\Manager\UserManager;
use Doyo\UserBundle\Model\User;

class UserContext implements Context
{
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(
        UserManager $userManager
    )
    {
        $this->userManager = $userManager;
    }

    /**
     * @Given there is user with username :username
     * @Given there is user with username :username and password :password
     * @param string $username
     * @param string $password
     */
    public function thereIsUser($username, $password='$3cr3t')
    {
        $userManager = $this->userManager;
        $user = $userManager->findByUsername($username);
        if(!$user instanceof User){
            $user = $userManager->create();
        }

        $email = $username.'@example.org';
        $user
            ->setUsername($username)
            ->setEmail($email)
            ->setPassword($password)
        ;

        $userManager->updateUser($user);
    }

    /**
     * @Given there are :num dummy users
     *
     * @param int $num
     */
    public function thereAreDummyUsers($num)
    {
        for($i=1;$i<=$num;$i++){
            $username = 'dummy_'.$i;
            $this->thereIsUser($username);

        }
    }
}