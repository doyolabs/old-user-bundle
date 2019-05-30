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

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class DoyoUserExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @var array
     */
    private static $doctrineDrivers = [
        'orm' => [
            'registry' => 'doctrine',
            'tag'      => 'doctrine.event_subscriber',
        ],
        'mongodb' => [
            'registry' => 'doctrine_mongodb',
            'tag'      => 'doctrine_mongodb.odm.event_subscriber',
        ],
        'couchdb' => [
            'registry'       => 'doctrine_couchdb',
            'tag'            => 'doctrine_couchdb.event_subscriber',
            'listener_class' => 'Doyo\UserBundle\Bridge\CouchDB\UserListener',
        ],
    ];

    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('api_platform', [
            'mapping' => [
                'paths' => [
                    __DIR__.'/../Resources/config/api_resources',
                ],
            ],
        ]);
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $processor     = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $map = [
            'model_manager_name',
            'api_platform',
        ];

        $container->setParameter('doyo_user.model.user.class', $config['user_class']);

        foreach ($map as $key) {
            $container->setParameter('doyo_user.'.$key, $config[$key]);
        }

        $loader->load('util.xml');
        $loader->load('command.xml');

        $this->loadDbDriver($loader, $container, $config);

        if (!empty($config['group'])) {
            $this->loadGroups($config['group'], $container, $loader, $config['db_driver']);
        }

        $container->setParameter('doyo_user.storage', $config['db_driver']);

        $container->setAlias('doyo_user.util.email_canonicalizer', $config['service']['email_canonicalizer']);
        $container->setAlias('doyo_user.util.username_canonicalizer', $config['service']['username_canonicalizer']);
        $container->setAlias('doyo_user.util.password_updater', $config['service']['password_updater']);
        $container->setAlias('doyo_user.user_manager', $config['service']['user_manager']);

        if ($config['api_platform']) {
            $loader->load('api-platform.xml');
        }

        if (!empty($config['group'])) {
            $this->loadGroups($config['group'], $container, $loader, $config['db_driver']);
        }
    }

    /**
     * @param $dbDriver
     *
     * @throws \Exception
     */
    private function loadGroups(array $config, ContainerBuilder $container, XmlFileLoader $loader, $dbDriver)
    {
        if ('custom' !== $dbDriver) {
            if (isset(self::$doctrineDrivers[$dbDriver])) {
                $loader->load('group.xml');
            } else {
                $loader->load(sprintf('%s_group.xml', $dbDriver));
            }
        }

        $container->setAlias('doyo_user.group_manager', new Alias($config['group_manager'], true));
        $container->setAlias('Doyo\UserBundle\Model\GroupManagerInterface', new Alias('doyo_user.group_manager', false));

        $this->remapParametersNamespaces($config, $container, [
            '' => [
                'group_class' => 'model.group.class',
            ],
        ]);
    }

    private function loadDbDriver(XmlFileLoader $loader, ContainerBuilder $container, $config)
    {
        if ('custom' !== $config['db_driver']) {
            if (isset(self::$doctrineDrivers[$config['db_driver']])) {
                $loader->load('doctrine.xml');
                $container->setAlias('doyo_user.doctrine_registry', new Alias(self::$doctrineDrivers[$config['db_driver']]['registry'], false));
            } else {
                $loader->load(sprintf('%s.xml', $config['db_driver']));
            }
            $container->setParameter($this->getAlias().'.backend_type_'.$config['db_driver'], true);
        }

        if (isset(self::$doctrineDrivers[$config['db_driver']])) {
            $definition = $container->getDefinition('doyo_user.object_manager');
            $definition->setFactory([new Reference('doyo_user.doctrine_registry'), 'getManager']);
        }
    }

    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (\array_key_exists($name, $config)) {
                $container->setParameter('doyo_user.'.$paramName, $config[$name]);
            }
        }
    }

    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!\array_key_exists($ns, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (\is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    $container->setParameter('doyo_user.'.sprintf($map, $name), $value);
                }
            }
        }
    }
}
