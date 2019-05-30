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

namespace Doyo\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('doyo_user');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $rootNode = $treeBuilder->root('doyo_user');
        }

        $rootNode
            ->children()
                ->scalarNode('db_driver')->defaultValue('orm')->end()
                ->scalarNode('user_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('model_manager_name')->defaultValue('default')->end()
                ->booleanNode('api_platform')->defaultValue(false)->end()
            ->end();

        $this->addServiceSection($rootNode);
        $this->addGroupSection($rootNode);

        return $treeBuilder;
    }

    private function addServiceSection(ArrayNodeDefinition $node)
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
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addGroupSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('group')
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('group_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('group_manager')->defaultValue('doyo_user.group_manager.default')->end()
                    ->end()
                ->end()
            ->end();
    }
}
