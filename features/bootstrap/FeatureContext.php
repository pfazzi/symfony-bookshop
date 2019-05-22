<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var Response|null
     */
    private $response;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When a demo scenario sends a request to :path
     */
    public function aDemoScenarioSendsARequestTo(string $path)
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived()
    {
        if (null === $this->response) {
            throw new \RuntimeException('No response received');
        }
    }

    /**
     * @Given there is an author named :arg1
     */
    public function thereIsAnAuthorNamed($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I add :arg1 header equal to :arg2
     */
    public function iAddHeaderEqualTo($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @When I send a :arg1 request to :arg2 with body:
     */
    public function iSendARequestToWithBody($arg1, $arg2, PyStringNode $string)
    {
        throw new PendingException();
    }

    /**
     * @Then the response should be in JSON
     */
    public function theResponseShouldBeInJson()
    {
        throw new PendingException();
    }

    /**
     * @Then the header :arg1 should be equal to :arg2
     */
    public function theHeaderShouldBeEqualTo($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then the JSON nodes should contain:
     */
    public function theJsonNodesShouldContain(TableNode $table)
    {
        throw new PendingException();
    }
}
