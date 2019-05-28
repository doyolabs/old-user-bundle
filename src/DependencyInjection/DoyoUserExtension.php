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
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class DoyoUserExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {

        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('util.xml');
        $loader->load('doctrine.xml');

        $container->setParameter('doyo_user.user_class', $config['user_class']);
        $container->setParameter('doyo_user.model_manager_name', $config['model_manager_name']);

        $container->setAlias('doyo_user.util.email_canonicalizer', $config['service']['email_canonicalizer']);
        $container->setAlias('doyo_user.util.username_canonicalizer', $config['service']['username_canonicalizer']);
        $container->setAlias('doyo_user.util.password_updater',$config['service']['password_updater']);
        $container->setAlias('doyo_user.user_manager',$config['service']['user_manager']);
    }
}
