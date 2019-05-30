<?php

namespace spec\Doyo\UserBundle\DependencyInjection\Compiler;

use Doyo\UserBundle\DependencyInjection\Compiler\ValidationPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ValidationPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ValidationPass::class);
    }

    function it_should_be_a_compiler_pass()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_should_not_process_when_storage_parameter_is_not_set(
        ContainerBuilder $builder
    )
    {
        $builder->hasParameter('doyo_user.storage')
            ->willReturn(false);
        $this->process($builder)->shouldReturn(null);
    }

    function it_should_not_process_when_using_custom_storage(
        ContainerBuilder $builder
    )
    {
        $builder->hasParameter('doyo_user.storage')->willReturn(true);
        $builder->getParameter('doyo_user.storage')->willReturn('custom');
        $this->process($builder)->shouldReturn(null);
    }

    function it_should_load_related_storage_validation_file(
        ContainerBuilder $builder,
        Definition $definition
    )
    {
        $builder->hasParameter('doyo_user.storage')
            ->willReturn(true);
        $builder->getParameter('doyo_user.storage')
            ->willReturn('orm');

        $builder->getDefinition('validator.builder')
            ->willReturn($definition);

        $callback = function($value){
            $value = $value[0];
            return false !== strpos($value,'orm.yaml');
        };

        $definition->addMethodCall('addYamlMapping',Argument::that($callback))
            ->shouldBeCalled();
        $this->process($builder)->shouldReturn(null);
    }
}
