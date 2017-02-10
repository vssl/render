<?php

namespace Tests;

use GuzzleHttp\Psr7\ServerRequest;
use Journey\Cache\Adapters\LocalAdapter;
use PHPUnit\Framework\TestCase;
use Vessel\Render\Renderer;
use Vessel\Render\Resolver;

class ResolverTest extends TestCase
{
    /**
     * Instance of our resolver
     *
     * @var \Vessel\Render\Resolver
     */
    protected $resolver;

    /**
     * PSR-7 Request.
     *
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * Initialize a resolver instance.
     */
    public function setUp()
    {
        $this->request = new ServerRequest(
            'GET',
            'http://127.0.0.1:1349/test-page'
        );
        $this->resolver = new Resolver($this->request, [
            'cache' => new LocalAdapter('/tmp'),
            'base_uri' => 'http://127.0.0.1:1349',
        ]);
    }

    /**
     * Ensure that the middleware implements the PSR-15 pattern.
     *
     * @return void
     */
    public function testResolver()
    {
        $page = $this->resolver->getRequest()->getAttribute('ws-page');
        $this->assertInternalType('array', $page);
        $this->assertEquals($page['status'], 200);
        $this->assertArrayHasKey('id', $page['data']);
        $this->assertInstanceOf(Renderer::class, $page['page']);
    }

    /**
     * Ensure that the resolver returns false for bad endpoints.
     *
     * @return void
     */
    public function testBadEndpoint()
    {
        $resolver = new Resolver($this->request, [
            'base_uri' => 'http://' . bin2hex(openssl_random_pseudo_bytes(16)) . ".com",
            'cache' => new LocalAdapter('/tmp')
        ]);
        $page = $resolver->getRequest()->getAttribute('ws-page');
        $this->assertArrayHasKey('error', $page);
    }
}
