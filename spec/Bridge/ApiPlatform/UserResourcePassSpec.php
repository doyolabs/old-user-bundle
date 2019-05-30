<?php

namespace spec\Doyo\UserBundle\Bridge\ApiPlatform;

use Doyo\UserBundle\Bridge\ApiPlatform\UserResourcePass;
use PhpSpec\ObjectBehavior;
use PHPUnit\Framework\Assert;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;

class UserResourcePassSpec extends ObjectBehavior
{
    function let()
    {
        $fs = new Filesystem();
        $fs->remove(sys_get_temp_dir().'/doyo-user');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserResourcePass::class);
    }

    function it_should_be_a_compiler_pass()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_should_not_process_when_api_platform_is_not_enabled(
        ContainerBuilder $containerBuilder
    )
    {
        $containerBuilder->getParameter('doyo_user.api_platform')
            ->willReturn(false);

        $this->process($containerBuilder)->shouldReturn(null);
    }

    function it_should_add_api_platform_resource(
        ContainerBuilder $containerBuilder
    )
    {
        $cacheDir = sys_get_temp_dir();

        $containerBuilder->getParameter('doyo_user.api_platform')
            ->willReturn(true);
        $containerBuilder->getParameter('kernel.cache_dir')
            ->willReturn($cacheDir);
        $containerBuilder->getParameter('kernel.debug')
            ->willReturn(true);
        $containerBuilder->getParameter('doyo_user.user_class')
            ->willReturn('SomeClass');

        $containerBuilder->prependExtensionConfig('api_platform', Argument::type('array'))
            ->shouldBeCalled();
        $this->process($containerBuilder);

        Assert::assertFileExists($path = $cacheDir.'/doyo-user/user-resource.yaml');
        Assert::assertFileExists($cacheDir.'/doyo-user/user-resource.yaml.meta');

        $contents = file_get_contents($path);
        Assert::assertStringContainsString('SomeClass', $contents);
    }
}
