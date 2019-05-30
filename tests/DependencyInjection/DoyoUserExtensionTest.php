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

namespace Doyo\UserBundle\Tests\DependencyInjection;

use App\Entity\User;
use Doyo\UserBundle\DependencyInjection\DoyoUserExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use spec\Doyo\UserBundle\Model\TestGroup;

class DoyoUserExtensionTest extends AbstractExtensionTestCase
{
    private $default = [
        'user_class' => User::class,
    ];

    protected function getContainerExtensions(): array
    {
        return [
            new DoyoUserExtension(),
        ];
    }

    public function testLoadedService()
    {
        $this->load($this->default);

        $this->assertContainerBuilderHasService('doyo_user.util.email_canonicalizer');
        $this->assertContainerBuilderHasService('doyo_user.util.username_canonicalizer');
        $this->assertContainerBuilderHasService('doyo_user.util.password_updater');
        $this->assertContainerBuilderHasService('doyo_user.user_manager');

        $this->assertContainerBuilderHasParameter('doyo_user.api_platform');

        $container = $this->container;
        $this->assertFalse($container->getParameter('doyo_user.api_platform'));
    }

    public function testApiPlatformLoading()
    {
        $config = array_merge($this->default,['api_platform' => true]);
        $this->load($config);

        $this->assertContainerBuilderHasService('doyo_user.user_denormalizer');
    }

    public function testGroupLoading()
    {
        $config = array_merge($this->default,[
            'group' => [
                'group_class' => TestGroup::class
            ]
        ]);

        $this->load($config);

        $this->assertContainerBuilderHasService('doyo_user.group_manager');
        $this->assertContainerBuilderHasParameter('doyo_user.model.group.class');
    }
}
