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

namespace Doyo\UserBundle\Tests\Command;

use Doyo\UserBundle\Command\CreateUserCommand;
use Doyo\UserBundle\Manager\UserManagerInterface;
use Doyo\UserBundle\Model\UserInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends TestCase
{
    public function testExecute()
    {
        $commandTester = $this->createCommandTester($this->getUserManager('user', 'pass', 'email', true, false));
        $exitCode      = $commandTester->execute([
            'username' => 'user',
            'email'    => 'email',
            'password' => 'pass',
        ], [
            'decorated'   => false,
            'interactive' => false,
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Created user user/', $commandTester->getDisplay());
    }

    public function testExecuteInteractiveWithQuestionHelper()
    {
        $application = new Application();

        $helper = $this->getMockBuilder('Symfony\Component\Console\Helper\QuestionHelper')
            ->setMethods(['ask'])
            ->getMock();

        $helper->expects($this->at(0))
            ->method('ask')
            ->willReturn('user');

        $helper->expects($this->at(1))
            ->method('ask')
            ->willReturn('email');

        $helper->expects($this->at(2))
            ->method('ask')
            ->willReturn('pass');

        $application->getHelperSet()->set($helper, 'question');

        $commandTester = $this->createCommandTester(
            $this->getUserManager('user', 'pass', 'email', true, false), $application
        );
        $exitCode = $commandTester->execute([], [
            'decorated'   => false,
            'interactive' => true,
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Created user user/', $commandTester->getDisplay());
    }

    /**
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
     *
     * @throws \ReflectionException
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
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

        if ($superadmin) {
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
            ->with($this->isInstanceOf(UserInterface::class));

        return $manager;
    }
}
