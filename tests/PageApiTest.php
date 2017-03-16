<?php

namespace Tests;

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\str;
use Journey\Cache\Adapters\LocalAdapter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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
     * @var \Journey\Cache\Adapters\LocalAdapter
     */
    protected $cache;

    /**
     * Initialize a PageApi instance.
     */
    public function setUp()
    {
        $this->cache = (new LocalAdapter('/tmp'))->clear();
        $this->request = new ServerRequest(
            'GET',
            'http://127.0.0.1:1349/test-page'
        );
        $this->resolver = new Resolver($this->request, [
            'cache' => $this->cache,
            'cache_ttl' => 300,
            'base_uri' => 'http://127.0.0.1:1349',
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
        $responseA = $this->api->call('GET', 'http://127.0.0.1:1349/cache-test');
        $responseB = $this->api->call('GET', 'http://127.0.0.1:1349/cache-test');
        $this->assertEquals(\GuzzleHttp\Psr7\str($responseA), \GuzzleHttp\Psr7\str($responseB));
    }

    /**
     * Test the clearEndpoint method.
     *
     * @return void
     */
    public function testClearEndpoint()
    {
        $response = $this->api->call('GET', 'http://127.0.0.1:1349/cache-test');
        $this->api->clearEndpoint('GET', 'http://127.0.0.1:1349/cache-test');
        $this->assertFalse($this->cache->get(md5('GET::http://127.0.0.1:1349/cache-test')));
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
        $originalIds = [1, 2, 300, 2122];
        $response = $this->api->getPagesById($originalIds);
        $value = json_decode((string) $response->getBody(), true);
        $ids = array_map(function ($page) {
            return $page['id'];
        }, $value['pages']);
        $this->assertEquals($originalIds, $ids);
    }
}
