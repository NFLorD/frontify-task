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

    public function get(string $path): void
    {
        $request = Request::create($path, Request::METHOD_GET);

        $this->response = $this->kernel->handle($request);
    }

    public function post(string $path, string $body): void
    {
        $request = Request::create($path, Request::METHOD_POST, [], [], [], [], $body);

        $this->response = $this->kernel->handle($request);
    }

    public function delete(string $path): void
    {
        $request = Request::create($path, Request::METHOD_DELETE);

        $this->response = $this->kernel->handle($request);
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @Then I should have no error
     */
    public function iShouldHaveNoError(): void
    {
        $code = $this->response->getStatusCode();
        if ($code > 399) {
            throw new \RuntimeException;
        }
    }

    /**
     * @Then I should have an error
     * @Then I should have an error with message :message
     */
    public function iShouldHaveAnError(string $message = null): void
    {
        $code = $this->response->getStatusCode();
        if ($code < 400) {
            throw new \RuntimeException;
        }

        if ($message) {
            $payload = json_decode($this->response->getContent(), true);
            if ($payload['error'] !== $message) {
                throw new \RuntimeException;
            }
        }
    }

    /**
     * @Then I should see :expectedNumber items
     */
    public function iShouldSeeItems(int $expectedNumber): void
    {
        $payload = json_decode($this->response->getContent(), true);
        if (count($payload['items']) !== $expectedNumber) {
            throw new \RuntimeException;
        }
    }
}