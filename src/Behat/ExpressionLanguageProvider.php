<?php


namespace Doyo\UserBundle\Behat;


use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    /**
     * @var TranslatorInterface $translator
     */
    private $translator;

    /**
     * @var RouterInterface $router
     */
    private $router;

    public function __construct(
        TranslatorInterface $translator,
        RouterInterface $router
    )
    {
        $this->translator = $translator;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new ExpressionFunction(
                'trans',
                [$this, 'transCompile'],
                [$this,'transEvaluate']
            ),
            new ExpressionFunction(
                'route',
                [$this,'routeCompile'],
                [$this, 'routeEvaluate']
            )
        ];
    }

    /**
     * Compile trans function
     *
     * @param string $id
     * @param string $domain
     * @param array $params
     * @return string Translated value
     */
    public function transCompile($id, $domain, $params=[])
    {
        $domain = preg_replace('/\"/',"", $domain);
        $id = preg_replace('/\"/',"", $id);
        $translator = $this->translator;
        return $translator->trans($id, $params, $domain);
    }

    public function transEvaluate($arguments, $id)
    {

    }

    /**
     * @param string $name
     * @param array $params
     * @return string generated route
     */
    public function routeCompile($name, $params = [])
    {

        return $this->router->generate($name,$params);
    }

    public function routeEvaluate($arguments, $name)
    {

    }
}