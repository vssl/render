<?php

namespace Tests;

use GuzzleHttp\Psr7\ServerRequest;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Journey\Cache\Adapters\LocalAdapter;
use PHPUnit\Framework\TestCase;
use Tests\Mocks\MockDelegate;
use Vessel\Render\Middleware;
use Vessel\Render\Resolver;

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
        Resolver::config([
            'cache' => new LocalAdapter('/tmp'),
            'base_uri' => 'http://127.0.0.1',
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
        $page = $this->delegate->getRequest()->getAttribute('ws-page');
        $this->assertEquals(404, $page['status']);
    }
}
