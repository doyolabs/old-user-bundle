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

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
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
    private static $doctrineDrivers = array(
        'orm' => array(
            'registry' => 'doctrine',
            'tag' => 'doctrine.event_subscriber',
        ),
        'mongodb' => array(
            'registry' => 'doctrine_mongodb',
            'tag' => 'doctrine_mongodb.odm.event_subscriber',
        ),
        'couchdb' => array(
            'registry' => 'doctrine_couchdb',
            'tag' => 'doctrine_couchdb.event_subscriber',
            'listener_class' => 'Doyo\UserBundle\Bridge\CouchDB\UserListener',
        ),
    );

    public function prepend(ContainerBuilder $container)
    {
    }

    public function load(array $configs, ContainerBuilder $container)
    {

        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('util.xml');
        $loader->load('command.xml');

        $this->loadDbDriver($loader, $container, $config);

        $container->setParameter('doyo_user.user_class', $config['user_class']);
        $container->setParameter('doyo_user.model_manager_name', $config['model_manager_name']);
        $container->setParameter('doyo_user.api_platform', $config['api_platform']);
        $container->setParameter('doyo_user.backend_type_orm', true);


        $container->setAlias('doyo_user.util.email_canonicalizer', $config['service']['email_canonicalizer']);
        $container->setAlias('doyo_user.util.username_canonicalizer', $config['service']['username_canonicalizer']);
        $container->setAlias('doyo_user.util.password_updater',$config['service']['password_updater']);
        $container->setAlias('doyo_user.user_manager',$config['service']['user_manager']);

        if($config['api_platform']){
            $this->loadApiPlatform($container);
        }

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
            $definition->setFactory(array(new Reference('doyo_user.doctrine_registry'), 'getManager'));
        }
    }

    private function loadApiPlatform($container)
    {
        $this->generateApiResourceCache($container);
    }

    private function generateApiResourceCache(ContainerBuilder $container)
    {
        $dir = __DIR__.'/../Resources/config/api_resources';
        if(!is_dir($dir)){
            mkdir($dir);
        }
        $path = $dir.'/User.yaml';
        $meta = $path.'.meta';
        $cache = new ConfigCache($path,false);

        if(!$cache->isFresh() || !is_file($meta)){
            $template = __DIR__.'/../Resources/config/template/user-resource.yaml';
            $contents = file_get_contents($template);
            $contents = strtr($contents,[
                '%doyo_user.user_class%' => $container->getParameter('doyo_user.user_class')
            ]);

            //file_put_contents($dir.'/user-resource.yaml', $contents, LOCK_EX);
            $resources = [new FileResource($template)];
            $cache->write($contents, $resources);
        }
    }
}
