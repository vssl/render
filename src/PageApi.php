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
     * Path to the API.
     *
     * @var string
     */
    protected $apiPath;

    /**
     * Configuration for the API.
     *
     * @var array
     */
    protected $config;

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
     * X-Render-Host
     *
     * @var string
     */
    protected $host;

    /**
     * Initialize the page api methods.
     */
    public function __construct(RequestInterface $request, array $config)
    {
        $this->config = $config;

        $this->cache = $config['cache'] ?? null;
        $this->ttl = $config['cache_ttl'] ?? 0;

        $this->host = $request->getUri()->getHost();
        $this->apiPath = '/' . (ltrim($config['base_path'], '/') ?? 'api');

        $this->initClient();
    }

    /**
     * Initialize the Guzzle client, allowing for re-initialization later.
     */
    private function initClient()
    {
        $this->http = new Client(
            [
                'base_uri' => rtrim($this->config['base_uri'], '/'),
                'headers' => [
                    'X-Render-Host' => $this->host,
                    'Content-Type' => 'application/json',
                    ...($this->config['headers'] ?? [])
                ]
            ]
        );
    }

    /**
     * Get a particular page from the API.
     *
     * @param  string $path path of the page
     * @param  string $additionalQueryParameters query string to append to the request
     * @return array|false
     */
    public function getPage($path, $additionalQueryParameters = '')
    {
        return $this->call("get", "/pages?slug=" . $path . '&' . $additionalQueryParameters);
    }

    /**
     * Get a particular page from the API.
     *
     * @param  integer $id  unique id of page to fetch data for.
     * @return array|false
     */
    public function getPageById($id)
    {
        return $this->call("get", "/pages?ids=" . $id);
    }

    /**
     * Get a set of pages from the API.
     *
     * @param  array $ids unique ids of pages to fetch data for.
     * @return array|false
     */
    public function getPagesById($ids)
    {
        $ids = array_filter(array_map(function ($id) {
            return is_numeric($id) ? (integer) $id : null;
        }, $ids));
        return $this->call("get", "/pages?ids=" . implode(",", $ids));
    }

    /**
     * Call a particular API endpoint.
     *
     * @param string $method
     * @param string $url
     * @param array $body
     * @param boolean $withPath
     * @param array $withCache
     * @return \Psr\Http\Message\ResponseInterface|false
     */
    public function call($method, $url, $body = null, $withPath = true, $withCache = true)
    {
        $url = $withPath ? rtrim($this->apiPath, '/') . '/' . ltrim($url, '/') : $url;
        $cacheKey = $this->host . '::' . strtoupper($method) . "::" . $url;

        $shouldCache = $withCache
            && !empty($this->cache)
            && !$this->config['isAuthenticated']
            && !$this->config['hasPassword'];

        try {
            if (!$shouldCache || !$value = $this->cache->get($cacheKey)) {
                if (!empty($body)) {
                    $response = $this->http->request(strtoupper($method), $url, ['json' => $body]);
                } else {
                    $response = $this->http->request(strtoupper($method), $url);
                }
            }
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } catch (ConnectException $e) {
            // Suppress network errors at this level.
        }

        if (!isset($response) && !empty($value)) {
            $response = \GuzzleHttp\Psr7\parse_response($value);
        } elseif ($shouldCache && !empty($response)) {
            $this->cache->set(
                $cacheKey,
                \GuzzleHttp\Psr7\str($response),
                !empty($this->ttl) ? time() + $this->ttl : 0
            );
        }

        return !empty($response) ? $response : false;
    }
}
