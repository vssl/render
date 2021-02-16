<?php

namespace Tests\Mocks;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vssl\Render\Middleware;
use Vssl\Render\Resolver;

class MockRequestHandler implements RequestHandlerInterface
{
    /**
     * The response handled by the process method.
     *
     * @var void
     */
    protected $request;

    /**
     * Use this PSR-15 middleware to pre-render a particular page.
     *
     * @param  Psr\Http\Message\ServerRequestInterface $request PSR-7 request
     * @return void
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->request = $request;
        return new Response();
    }

    /**
     * Return the request object.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}

