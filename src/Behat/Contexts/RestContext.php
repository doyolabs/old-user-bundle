<?php


namespace Doyo\UserBundle\Behat\Contexts;


use Behat\Gherkin\Node\PyStringNode;
use Behatch\Context\RestContext as BaseRestContext;

class RestContext extends BaseRestContext
{
    /**
     * @Given I send a JSON :method request to :url
     * @Given I send a JSON :method request to :url with :body
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
