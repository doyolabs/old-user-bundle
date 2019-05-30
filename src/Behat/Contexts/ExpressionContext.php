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

namespace Doyo\UserBundle\Behat\Contexts;

use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doyo\UserBundle\Behat\ExpressionLanguageProvider;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpKernel\KernelInterface;

class ExpressionContext implements Context, KernelAwareContext
{
    /**
     * @var ExpressionLanguage
     */
    private $expressionLanguage;

    public function setKernel(KernelInterface $kernel)
    {
        $lang       = new ExpressionLanguage();
        $translator = $kernel->getContainer()->get('translator');
        $router     = $kernel->getContainer()->get('router');
        $provider   = new ExpressionLanguageProvider($translator, $router);
        $lang->registerProvider($provider);

        $this->expressionLanguage = $lang;
    }

    /**
     * @param string $content
     *
     * @return string|string[]|null
     */
    public function compile($content)
    {
        $expression = $this->expressionLanguage;
        $callback   = function ($match) use ($expression) {
            return $expression->compile($match[0]);
        };

        $pattern = '/(trans|route)\(.*\)/';

        return preg_replace_callback($pattern, $callback, $content);
    }
}
