<?php


namespace Doyo\UserBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ValidationPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('doyo_user.storage')) {
            return;
        }

        $storage = $container->getParameter('doyo_user.storage');

        if ('custom' === $storage) {
            return;
        }

        $validationFile = __DIR__.'/../../Resources/config/storage-validation/'.$storage.'.yaml';

        $container->getDefinition('validator.builder')
            ->addMethodCall('addYamlMapping', array($validationFile));
    }
}