<?php

namespace Tests;

use GuzzleHttp\Psr7\ServerRequest;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Journey\Cache\Adapters\LocalAdapter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tests\Mocks\MockDelegate;
use Vssl\Render\Middleware;
use Vssl\Render\Resolver;

class MiddlewareTest extends TestCase
{
    /**
     * Mock delegate.
     *
     * @var \Tests\Mocks\MockDelegate
     */
    protected $delegate;

    /**
     * Setup our middleware test environment.
     */
    public function setUp()
    {
        $this->delegate = new MockDelegate();
        $cache = (new LocalAdapter('/tmp'))->clear();
        Resolver::config([
            'cache' => $cache,
            'base_uri' => 'http://127.0.0.1:1349',
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
        $middleware->process(new ServerRequest("GET", "http://127.0.0.1/404"), $this->delegate);
        $this->assertInstanceOf(ServerRequestInterface::class, $this->delegate->getRequest());
    }

    /**
     * Test the middleware return value.
     *
     * @return void
     */
    public function testMiddlewareResponse()
    {
        $middleware = new Middleware();
        $result = $middleware->process(new ServerRequest("GET", "http://127.0.0.1/api/v1/pages"), $this->delegate);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}
