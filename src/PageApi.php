<?php

namespace Vssl\Render;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Journey\Cache\CacheAdapterInterface;
use Psr\Http\Message\RequestInterface;

class PageApi
{
    /**
     * Guzzle HTTP interface.
     *
     * @var \GuzzleHTTP\Client
     */
    protected $http;

    /**
     * A cache adapter for storing results.
     *
     * @var \Journey\Cache\CacheAdapterInterface
     */
    protected $cache;

    /**
     * Initialize the page api methods.
     */
    public function __construct(RequestInterface $request, array $config)
    {
        $this->cache = $config['cache'];
        $this->http = new Client([
            'base_uri' => $config['base_uri'],
            'headers' => [
                'X-Render-Host: ' . $request->getUri()->getHost(),
            ]
        ]);
    }

    /**
     * Get a particular page from the api.
     *
     * @param  string $path path of the page
     * @return array|false
     */
    public function getPage($path)
    {
        return $this->call("get", "/api/pages?slug=" . $path);
    }

    /**
     * Call a particular api endpoint.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function call($method, $url)
    {
        try {
            return $this->http->request(strtoupper($method), $url);
        } catch (ClientException $e) {
            return $e->getResponse();
        } catch (ConnectException $e) {
            return false;
        }
    }
}
