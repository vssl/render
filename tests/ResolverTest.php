<?php

namespace Tests;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Journey\Cache\Adapters\LocalAdapter;
use Journey\Cache\CacheAdapterInterface;
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
            'https://demo.vssl.io/test-page'
        );
        $this->resolver = new Resolver($this->request, [
            'cache' => $cache,
            'base_uri' => 'https://api.vssl.io'
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
        new Resolver($this->request, ['cache' => 'bad cache']);
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

    /**
     * Test that getConfig does return the configuration file.
     *
     * @return void
     */
    public function testGetConfig()
    {
        $this->assertEquals('https://api.vssl.io', $this->resolver->getConfig()['base_uri']);
    }

    /**
     * Test that getConfig does return the configuration file.
     *
     * @return void
     */
    public function testGetCache()
    {
        $this->assertInstanceOf(CacheAdapterInterface::class, $this->resolver->getCache());
    }
}
