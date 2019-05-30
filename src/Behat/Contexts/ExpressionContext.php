<?php


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
        $lang = new ExpressionLanguage();
        $translator = $kernel->getContainer()->get('translator');
        $router = $kernel->getContainer()->get('router');
        $provider = new ExpressionLanguageProvider($translator, $router);
        $lang->registerProvider($provider);

        $this->expressionLanguage = $lang;
    }

    /**
     * @param   string $content
     * @return  string|string[]|null
     */
    public function translate($content)
    {
        $expression = $this->expressionLanguage;
        $callback = function($match) use($expression){
            return $expression->compile($match[0]);
        };

        $pattern = '/(trans|route)\(.*\)/';

        $content = preg_replace_callback($pattern, $callback,$content);

        return $content;
    }
}