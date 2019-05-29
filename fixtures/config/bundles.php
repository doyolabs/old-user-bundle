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

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class                    => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class                              => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class                      => ['all' => true],
    Nelmio\CorsBundle\NelmioCorsBundle::class                                => ['all' => true],
    Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle::class           => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class                     => ['all' => true],
    ApiPlatform\Core\Bridge\Symfony\Bundle\ApiPlatformBundle::class          => ['all' => true],
    Doyo\UserBundle\DoyoUserBundle::class                                    => ['all' => true],
    Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle::class => ['all' => true],
    Symfony\Bundle\WebServerBundle\WebServerBundle::class                    => ['dev' => true],
];
