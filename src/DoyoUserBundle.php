<?php


namespace Doyo\UserBundle;


use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoyoUserBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $this->addRegisterMappingPass($container);
    }

    final private function addRegisterMappingPass(ContainerBuilder $container)
    {
        $mappings = [
            realpath(__DIR__.'/Resources/config/doctrine-mapping') => 'Doyo\UserBundle\Model'
        ];

        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createXmlMappingDriver($mappings,['default'])
        );
    }
}
