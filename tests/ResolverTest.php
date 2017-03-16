<?php

namespace Tests;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Journey\Cache\Adapters\LocalAdapter;
use PHPUnit\Framework\TestCase;
use Vssl\Render\Renderer;
use Vssl\Render\Resolver;
use Vssl\Render\ResolverException;

class ResolverTest extends TestCase
{
    /**
     * Instance of our resolver
     *
     * @var \Vssl\Render\Resolver
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
        $cache = (new LocalAdapter('/tmp'))->clear();
        $this->request = new ServerRequest(
            'GET',
            'http://127.0.0.1:1349/test-page'
        );
        $this->resolver = new Resolver($this->request, [
            'cache' => $cache,
            'base_uri' => 'http://127.0.0.1:1349',
        ]);
    }

    /**
     * Should throw an exception when no cache adapter is used.
     *
     * @return void
     */
    public function testInvalidCache()
    {
        $this->expectException(ResolverException::class);
        new Resolver($this->request, []);
    }

    /**
     * Ensure that the middleware implements the PSR-15 pattern.
     *
     * @return void
     */
    public function testResolver()
    {
        $page = $this->resolver->getRequest()->getAttribute('vssl-page');
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
        $page = $resolver->getRequest()->getAttribute('vssl-page');
        $this->assertArrayHasKey('error', $page);
    }

    /**
     * Test that when a non json response is given, only 'false' is returned.
     *
     * @return void
     */
    public function testNonJsonDecode()
    {
        $this->assertFalse($this->resolver->decodePage(new Response()));
    }
}
