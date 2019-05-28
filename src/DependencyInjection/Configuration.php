<?php


namespace Doyo\UserBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('doyo_user');

        //@codeCoverageIgnoreStart
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $rootNode = $treeBuilder->root('doyo_user');
        }
        //@codeCoverageIgnoreEnd

        $rootNode
            ->children()
                ->scalarNode('user_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('model_manager_name')->defaultValue('default')->end()
            ->end()
        ;
        $this->addServiceSection($rootNode);

        return $treeBuilder;
    }

    final private function addServiceSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('service')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('email_canonicalizer')->defaultValue('doyo_user.util.canonicalizer.default')->end()
                            ->scalarNode('username_canonicalizer')->defaultValue('doyo_user.util.canonicalizer.default')->end()
                            ->scalarNode('password_updater')->defaultValue('doyo_user.util.password_updater.default')->end()
                            ->scalarNode('user_manager')->defaultValue('doyo_user.user_manager.default')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}