<?php

namespace Tests;

use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tests\Mocks\ArrayCache;
use Vssl\Render\Renderer;
use Vssl\Render\Resolver;

class PageApiTest extends TestCase
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
     * PSR-7 Request.
     *
     * @var \Vssl\Render\PageApi
     */
    protected $api;

    /**
     * Cache instance.
     *
     * @var \Psr\SimpleCache\CacheInterface
     */
    protected $cache;

    /**
     * Initialize a PageApi instance.
     */
    public function setUp(): void
    {
        $this->cache = new ArrayCache();
        $this->request = new ServerRequest(
            'GET',
            'https://demo.vssl.io/test-page'
        );
        $this->resolver = new Resolver($this->request, [
            'cache' => $this->cache,
            'cache_ttl' => 300,
            'base_uri' => getenv('VSSL_TEST_API_URL') ?: 'https://api.vssl.io',
        ]);
        $this->api = $this->resolver->getPageApi();
    }

    /**
     * Tests the page api's ability to read/store via cache adapter.
     *
     * @return void
     */
    public function testCallCache()
    {
        $this->cache->clear();
        $responseA = $this->api->call('GET', 'https://demo.vssl.io/cache-test');
        $responseB = $this->api->call('GET', 'https://demo.vssl.io/cache-test');
        $this->assertEquals(Message::toString($responseA), Message::toString($responseB));
    }

    /**
     * Test the getPagesById() method.
     *
     * @return void
     */
    public function testGetPagesById()
    {
        $result = $this->api->getPagesById([1]);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /**
     * Test that getPagesById() method is returning the ids passed in.
     *
     * @return void
     */
    public function testGetPagesByIdValues()
    {
        $originalIds = [69, 71, 72];
        $response = $this->api->getPagesById($originalIds);
        $data = json_decode((string) $response->getBody(), true);
        // The mock router returns { "pages": [...] }
        $pages = $data['pages'] ?? $data['data'] ?? $data ?? [];
        $ids = array_map(function ($page) {
            return $page['id'];
        }, $pages);
        $this->assertEquals($originalIds, $ids);
    }
}
