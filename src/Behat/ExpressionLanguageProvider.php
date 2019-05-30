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

namespace Doyo\UserBundle\Behat;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $this->translator = $translator;
        $this->router     = $router;
    }

    public function getFunctions()
    {
        return [
            new ExpressionFunction(
                'trans',
                [$this, 'transCompile'],
                [$this, 'transEvaluate']
            ),
            new ExpressionFunction(
                'route',
                [$this, 'routeCompile'],
                [$this, 'routeEvaluate']
            ),
        ];
    }

    /**
     * Compile trans function.
     *
     * @param string $id
     * @param string $domain
     * @param array  $params
     *
     * @return string Translated value
     */
    public function transCompile($id, $domain, $params=[])
    {
        $domain     = $this->normalizeValue($domain);
        $id         = $this->normalizeValue($id);
        $translator = $this->translator;

        return $translator->trans($id, $params, $domain);
    }

    public function transEvaluate($arguments, $id)
    {
    }

    /**
     * @param string $name
     * @param string $params
     *
     * @return string generated route
     */
    public function routeCompile($name, $params=null)
    {
        $name = $this->normalizeValue($name);
        if (null !== $params) {
            eval('$params = '.$params.';');
        } else {
            $params = [];
        }

        return $this->router->generate($name, $params);
    }

    public function routeEvaluate($arguments, $name)
    {
    }

    private function normalizeValue($value)
    {
        return preg_replace('/\"/', '', $value);
    }
}
