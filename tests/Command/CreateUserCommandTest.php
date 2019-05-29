<?php

namespace Doyo\UserBundle\Tests\Command;

use Doyo\UserBundle\Command\CreateUserCommand;
use Doyo\UserBundle\Manager\UserManagerInterface;
use Doyo\UserBundle\Model\UserInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Doyo\UserBundle\Bridge\ORM\UserManager;

class CreateUserCommandTest extends TestCase
{
    public function testExecute()
    {
        $commandTester = $this->createCommandTester($this->getUserManager('user', 'pass', 'email', true, false));
        $exitCode = $commandTester->execute(array(
            'username' => 'user',
            'email' => 'email',
            'password' => 'pass',
        ), array(
            'decorated' => false,
            'interactive' => false,
        ));

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Created user user/', $commandTester->getDisplay());
    }

    public function testExecuteInteractiveWithQuestionHelper()
    {
        $application = new Application();

        $helper = $this->getMockBuilder('Symfony\Component\Console\Helper\QuestionHelper')
            ->setMethods(array('ask'))
            ->getMock();

        $helper->expects($this->at(0))
            ->method('ask')
            ->will($this->returnValue('user'));

        $helper->expects($this->at(1))
            ->method('ask')
            ->will($this->returnValue('email'));

        $helper->expects($this->at(2))
            ->method('ask')
            ->will($this->returnValue('pass'));

        $application->getHelperSet()->set($helper, 'question');

        $commandTester = $this->createCommandTester(
            $this->getUserManager('user', 'pass', 'email', true, false), $application
        );
        $exitCode = $commandTester->execute(array(), array(
            'decorated' => false,
            'interactive' => true,
        ));

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Created user user/', $commandTester->getDisplay());
    }

    /**
     * @param UserManagerInterface  $userManager
     * @param Application|null $application
     *
     * @return CommandTester
     */
    private function createCommandTester(UserManagerInterface $userManager, Application $application = null)
    {
        if (null === $application) {
            $application = new Application();
        }

        $application->setAutoExit(false);

        $command = new CreateUserCommand($userManager);

        $application->add($command);

        return new CommandTester($application->find('doyo:user:create'));
    }

    /**
     * @param $username
     * @param $password
     * @param $email
     * @param $active
     * @param $superadmin
     * @return \PHPUnit\Framework\MockObject\MockObject
     * @throws \ReflectionException
     */
    private function getUserManager($username, $password, $email, $active, $superadmin)
    {
        $manager = $this->getMockBuilder(UserManagerInterface::class)
            ->getMock();

        $user = $this->getMockBuilder(UserInterface::class)
            ->getMock();

        $user->expects($this->once())
            ->method('setUsername')
            ->with($username)
            ->willReturn($user);
        $user->expects($this->once())
            ->method('setPlainPassword')
            ->with($password)
            ->willReturn($user);
        $user->expects($this->once())
            ->method('setEmail')
            ->with($email)
            ->willReturn($user);
        $user->expects($this->once())
            ->method('setEnabled')
            ->with($active)
            ->willReturn($user);

        if($superadmin){
            $user->expects($this->once())
                ->method('addRole')
                ->with('ROLE_SUPER_ADMIN')
                ->willReturn($user);
        }


        $manager
            ->expects($this->any())
            ->method('createUser')
            ->willReturn($user);

        $manager
            ->expects($this->once())
            ->method('updateUser')
            ->with($this->isInstanceOf(UserInterface::class))
        ;

        return $manager;
    }
}
