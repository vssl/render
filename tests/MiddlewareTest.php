<?php

namespace Tests;

use GuzzleHttp\Psr7\ServerRequest;
use Journey\Cache\Adapters\LocalAdapter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Tests\Mocks\MockRequestHandler;
use Vssl\Render\Middleware;
use Vssl\Render\Resolver;

class MiddlewareTest extends TestCase
{
    /**
     * Mock handler.
     *
     * @var \Tests\Mocks\MockHandler
     */
    protected $handler;

    /**
     * Setup our middleware test environment.
     */
    public function setUp()
    {
        $this->handler = new MockRequestHandler();
        $cache = (new LocalAdapter('/tmp'))->clear();
        Resolver::config([
            'cache' => $cache,
            'base_uri' => 'https://api.vssl.io',
        ]);
    }

    /**
     * Ensure that the middleware implements the PSR-15 pattern.
     *
     * @return void
     */
    public function testMiddlewareResponder()
    {
        $middleware = new Middleware();
        $this->assertInstanceOf(MiddlewareInterface::class, $middleware);
    }

    /**
     * Test the middleware process method
     *
     * @return void
     */
    public function testMiddlewareProcessor()
    {
        $middleware = new Middleware();
        $middleware->process(new ServerRequest("GET", "https://demo.vssl.io/test-page"), $this->handler);
        $this->assertInstanceOf(ServerRequestInterface::class, $this->handler->getRequest());
    }

    /**
     * Test the middleware return value.
     *
     * @return void
     */
    public function testMiddlewareResponse()
    {
        $middleware = new Middleware();
        $result = $middleware->process(new ServerRequest("GET", "https://demo.vssl.io/test-page"), $this->handler);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}
