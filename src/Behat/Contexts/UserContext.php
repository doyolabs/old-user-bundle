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

namespace Doyo\UserBundle\Behat\Contexts;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Doyo\UserBundle\Manager\UserManagerInterface;
use Doyo\UserBundle\Model\User;
use Doyo\UserBundle\Model\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\Routing\RouterInterface;

class UserContext implements Context
{
    /**
     * @var UserManagerInterface
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

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        UserManagerInterface $userManager,
        JWTManager $jwtManager,
        RouterInterface $router
    ) {
        $this->userManager = $userManager;
        $this->jwtManager  = $jwtManager;
        $this->router      = $router;
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
     *
     * @param string $username
     * @param string $password
     * @param string $fullName
     *
     * @return UserInterface
     */
    public function thereIsUser($username, $password='s3cr3t', $fullName = 'Test User')
    {
        /** @var \App\Entity\User $user */
        $userManager = $this->userManager;
        $user        = $userManager->findUserByUsername($username);

        if (!$user instanceof User) {
            $user = $userManager->createUser();
            $email = $username.'@example.org';
            $user
                ->setUsername($username)
                ->setEmail($email)
                ->setPlainPassword($password)
                ->setFullname($fullName)
                ->setEnabled(true);

            $userManager->updateUser($user);
        }

        return $user;
    }

    /**
     * @Given there are :num dummy users
     *
     * @param int $num
     */
    public function thereAreDummyUsers($num)
    {
        for ($i=1; $i <= $num; ++$i) {
            $username = 'dummy_'.$i;
            $this->thereIsUser($username);
        }
    }

    /**
     * @Given I have logged in with username :username
     * @Given I have logged in with username :username and password :password
     *
     * @param string $username
     * @param string $password
     */
    public function iHaveLoggedInWithUser($username, $password='s3cr3t')
    {
        $user  = $this->thereIsUser($username, $password);
        $token = $this->jwtManager->create($user);
        $this->restContext->iAddHeaderEqualTo('Authorization', 'Bearer '.$token);
    }

    /**
     * @Given I send request api for user :username
     * @Given I request api for user :username
     * @param string $username
     */
    public function iRequestApiForUser($username)
    {
        $router      = $this->router;
        $user        = $this->thereIsUser($username);
        $restContext = $this->restContext;

        $url = $router->generate('api_users_get_item', ['id' => $user->getId()]);

        $restContext->iSendJsonRequestTo('GET', $url);
    }

    /**
     * @Given I don't have user with username :username
     * @param string $username
     */
    public function iDonTHaveUser($username)
    {
        $manager = $this->userManager;
        $user = $manager->findUserByUsername($username);
        if($user instanceof UserInterface){
            $manager->deleteUser($user);
        }
    }

    /**
     * @Given I send api request to create user with:
     * @param PyStringNode $node
     */
    public function iSendApiRequestToCreateUser(PyStringNode $node)
    {
        $url = $this->router->generate('api_users_post_collection');

        $this->restContext->iSendJsonRequestToWithBody('POST', $url, $node);
    }

    /**
     * @Given I have logged in as admin
     */
    public function iHaveLoggedInAsAdmin()
    {
        $manager = $this->userManager;
        $jwtManager = $this->jwtManager;
        $user = $manager->findUserByUsername('admin');
        if(!$user instanceof UserInterface){
            $user = $manager->createUser();

        }

        $user->setUsername('admin');
        $user->setPlainPassword('s3cr3t');
        $user->addRole('ROLE_USER_ADMIN');
        $user->setEmail('admin@example.org');

        $manager->updateUser($user);

        $token = $jwtManager->create($user);
        $this->restContext->iAddHeaderEqualTo('Authorization', 'Bearer '.$token);
    }

    /**
     * @Given I send api to update user :username with:
     * @param PyStringNode $node
     */
    public function iSendApiToUpdateUserWith($username, PyStringNode $node)
    {
        $user = $this->thereIsUser($username);

        $url = $this->router->generate(
            'api_users_put_item',
            ['id' => $user->getId()]
        );

        $this->restContext->iSendJsonRequestToWithBody('PUT', $url, $node);
    }

    /**
     * @Given I send api to delete user :username
     * @param string $username
     */
    public function iSendApiToDeleteUser($username)
    {
        $user = $this->thereIsUser($username);

        $url = $this->router->generate(
            'api_users_delete_item',
            ['id' => $user->getId()]
        );
        $this->restContext->iSendJsonRequestTo('DELETE', $url);
    }
}
