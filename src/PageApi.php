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
     * Number of seconds responses should be cached for.
     *
     * @var integer
     */
    protected $ttl;

    /**
     * Initialize the page api methods.
     */
    public function __construct(RequestInterface $request, array $config)
    {
        $this->ttl = $config['cache_ttl'];
        $this->cache = $config['cache'];
        $this->http = new Client([
            'base_uri' => $config['base_uri'],
            'headers' => [
                'X-Render-Host' => $request->getUri()->getHost(),
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
     * Get a particular page from the api.
     *
     * @param  array $ids unique ids of pages to fetch data for.
     * @return array|false
     */
    public function getPagesById($ids)
    {
        $ids = array_filter(array_map(function ($id) {
            return is_numeric($id) ? (integer) $id : null;
        }, $ids));
        return $this->call("get", "/api/pages?ids=" . implode(",", $ids));
    }

    /**
     * Clear the cache of a given endpoint immediately.
     *
     * @param  string $method get/post/put
     * @param  string $url    endpoint to hit
     * @return $this
     */
    public function clearEndpoint($method, $url)
    {
        $cacheKey = md5(strtoupper($method) . "::" . $url);
        $this->cache->delete($cacheKey);
        return $this;
    }

    /**
     * Call a particular api endpoint.
     * @param string $method
     * @param string $url
     * @return \Psr\Http\Message\ResponseInterface|false
     */
    public function call($method, $url)
    {
        $cacheKey = md5(strtoupper($method) . "::" . $url);
        try {
            if (!$value = $this->cache->get($cacheKey)) {
                $response = $this->http->request(strtoupper($method), $url);
            }
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } catch (ConnectException $e) {
            // Suppress network errors at this level.
        }
        if (!isset($response) && $value) {
            $response = \GuzzleHttp\Psr7\parse_response($value);
        } elseif ($this->ttl && !empty($response)) {
            $this->cache->set($cacheKey, \GuzzleHttp\Psr7\str($response), time() + $this->ttl);
        }
        return !empty($response) ? $response : false;
    }
}
