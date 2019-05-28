<?php

namespace Doyo\UserBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateJWTKeyCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $app = new Application($kernel);
        $cmd = $app->find('doyo:generate:jwt-keys');
        $tester = new CommandTester($cmd);
        $tester->execute([]);

        $output = $tester->getDisplay(true);
        $this->assertStringContainsString('Generating private key in', $output);
        $this->assertStringContainsString('Generating RSA private key, 4096 bit long modulus', $output);
        $this->assertStringContainsString('Generating public key in', $output);
        $this->assertStringContainsString('chmod 0775', $output);
    }
}
