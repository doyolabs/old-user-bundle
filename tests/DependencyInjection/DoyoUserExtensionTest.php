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

class DoyoUserExtensionTest extends AbstractExtensionTestCase
{
    private $default = [
        'user_class' => User::class
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
}
