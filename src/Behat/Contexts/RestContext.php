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

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behatch\Context\RestContext as BaseRestContext;

class RestContext extends BaseRestContext
{
    /**
     * @var ExpressionContext
     */
    private $expressionContext;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->expressionContext = $scope->getEnvironment()->getContext(ExpressionContext::class);
    }

    /**
     * @Given I send a JSON :method request to :url
     * @Given I send a JSON :method request to :url with :body
     *
     * @param string $method
     * @param string $url
     * @param array  $files
     */
    public function iSendJsonRequestTo($method, $url, PyStringNode $body = null, $files = [])
    {
        $url = $this->expressionContext->compile($url);
        $this->iAddHeaderEqualTo('Content-Type', 'application/json');
        $this->iAddHeaderEqualTo('Accept', 'application/json');
        $this->iSendARequestTo($method, $url, $body);
    }

    /**
     * @Given I send a JSON :method request to :url with body:
     *
     * @param string $method
     * @param string $url
     */
    public function iSendJsonRequestToWithBody($method, $url, PyStringNode $body = null)
    {
        $this->iSendJsonRequestTo($method, $url, $body);
    }
}
