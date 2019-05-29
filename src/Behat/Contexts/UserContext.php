<?php


namespace Doyo\UserBundle\Behat\Contexts;


use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doyo\UserBundle\Manager\UserManager;
use Doyo\UserBundle\Model\User;
use Doyo\UserBundle\Model\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;

class UserContext implements Context
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var RestContext
     */
    private $restContext;

    /**
     * @var JWTManager
     */
    private $jwtManager;

    public function __construct(
        UserManager $userManager,
        JWTManager $jwtManager
    )
    {
        $this->userManager = $userManager;
        $this->jwtManager = $jwtManager;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scenarioScope)
    {
        $this->restContext = $scenarioScope->getEnvironment()->getContext(RestContext::class);
    }

    /**
     * @Given there is user with username :username
     * @Given there is user with username :username and password :password
     * @param string $username
     * @param string $password
     * @return UserInterface
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
            ->setPlainPassword($password)
        ;

        $userManager->updateUser($user);

        return $user;
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

    /**
     * @Given I have logged in with username :username
     * @Given I have logged in with username :username and password :password
     * @param string $username
     * @param string $password
     */
    public function iHaveLoggedInWithUser($username, $password='s3cr3t')
    {
        $user = $this->thereIsUser($username, $password);
        $token = $this->jwtManager->create($user);
        $this->restContext->iAddHeaderEqualTo('Authorization','Bearer '.$token);
    }
}
