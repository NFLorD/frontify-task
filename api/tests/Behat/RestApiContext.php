<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class RestApiContext implements Context
{
    private Response $response;

    public function __construct(
        private KernelInterface $kernel
    ) { }

    public function get(string $path)
    {
        $request = Request::create($path, Request::METHOD_GET);

        $this->response = $this->kernel->handle($request);
    }

    public function post(string $path, string $body)
    {
        $request = Request::create($path, Request::METHOD_POST, [], [], [], [], $body);

        $this->response = $this->kernel->handle($request);
    }

    public function delete(string $path)
    {
        $request = Request::create($path, Request::METHOD_DELETE);

        $this->response = $this->kernel->handle($request);
    }

    /**
     * @Then I should have no error
     */
    public function iShouldHaveNoError()
    {
        $code = $this->response->getStatusCode();
        if ($code > 299) {
            throw new \RuntimeException;
        }
    }

    /**
     * @Then I should have an error
     */
    public function iShouldHaveAnError()
    {
        $code = $this->response->getStatusCode();
        if ($code < 299) {
            throw new \RuntimeException;
        }
    }

    /**
     * @Then I should see :expectedNumber items
     */
    public function iShouldSeeItems($expectedNumber)
    {
        $items = json_decode($this->response->getContent());
        if (count($items) !== $expectedNumber) {
            throw new \RuntimeException;
        }
    }
}