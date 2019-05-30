<?php

namespace spec\Doyo\UserBundle\Bridge\ApiPlatform;

use App\Entity\Group;
use App\Entity\User;
use Doyo\UserBundle\Bridge\ApiPlatform\UserResourcePass;
use PhpSpec\ObjectBehavior;
use PHPUnit\Framework\Assert;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class UserResourcePassSpec extends ObjectBehavior
{
    private $cacheDir;

    function let()
    {
        $this->cacheDir = __DIR__.'/../../../src/Resources/config/api_resources';
        $finder = Finder::create()
            ->in($this->cacheDir)
            ->name('User*');
        $fs = new Filesystem();
        $fs->remove($finder->files());
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
        $cacheDir = $this->cacheDir;

        $containerBuilder->getParameter('doyo_user.api_platform')
            ->willReturn(true);
        $containerBuilder->getParameter('kernel.cache_dir')
            ->willReturn($cacheDir);
        $containerBuilder->getParameter('kernel.debug')
            ->willReturn(true);
        $containerBuilder->getParameter('doyo_user.model.user.class')
            ->willReturn(User::class);

        $containerBuilder->hasParameter('doyo_user.model.group.class')
            ->willReturn(true);
        $containerBuilder->getParameter('doyo_user.model.group.class')
            ->willReturn(Group::class);

        $this->process($containerBuilder);

        Assert::assertFileExists($path = $cacheDir.'/User.yaml');
        Assert::assertFileExists($cacheDir.'/User.yaml.meta');

        $contents = file_get_contents($path);
        Assert::assertStringContainsString(User::class, $contents);
    }
}
