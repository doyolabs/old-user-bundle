<?php


namespace Doyo\UserBundle\Tests\DependencyInjection;


use Doyo\UserBundle\DependencyInjection\DoyoUserExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class DoyoUserExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new DoyoUserExtension()
        ];
    }


    public function testLoad()
    {
        $this->load();

        $this->assertContainerBuilderHasParameter('doyo.user_class');
    }
}
