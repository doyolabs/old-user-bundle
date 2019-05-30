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

use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behatch\Context\RestContext as BaseRestContext;
use Symfony\Component\HttpKernel\KernelInterface;

class RestContext extends BaseRestContext
{
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
