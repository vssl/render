<?php

namespace Tests;

use GuzzleHttp\Psr7\ServerRequest;
use Journey\Cache\Adapters\LocalAdapter;
use PHPUnit\Framework\TestCase;
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
     * Initialize a PageApi instance.
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
        $this->api = $this->resolver->getPageApi();
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
