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
        $this->cache = $config['cache'] ?? null;
        $this->ttl = $config['cache_ttl'] ?? 0;

        $this->host = $request->getUri()->getHost();
        $this->apiPath = '/' . (ltrim($config['base_path'], '/') ?? 'api');

        $this->http = new Client([
            'base_uri' => rtrim($config['base_uri'], '/'),
            'headers' => [
                'X-Render-Host' => $request->getUri()->getHost(),
            ]
        ]);
    }

    /**
     * Get a particular page from the API.
     *
     * @param  string $path path of the page
     * @return array|false
     */
    public function getPage($path)
    {
        return $this->call("get", "/pages?slug=" . $path);
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

    public function getMenus()
    {
        return $this->call("get", "/menus");
    }

    public function getMenuById($id)
    {
        return $this->call("get", "/menus/" . $id);
    }

    /**
     * Call a particular API endpoint.
     * @param string $method
     * @param string $url
     * @param boolean $withPath
     * @return \Psr\Http\Message\ResponseInterface|false
     */
    public function call($method, $url, $withPath = true)
    {
        $url = $withPath ? rtrim($this->apiPath, '/') . '/' . ltrim($url, '/') : $url;
        $cacheKey = $this->host . '::' . strtoupper($method) . "::" . $url;

        try {
            if (empty($this->cache) || !$value = $this->cache->get($cacheKey)) {
                $response = $this->http->request(strtoupper($method), $url);
            }
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } catch (ConnectException $e) {
            // Suppress network errors at this level.
        }

        if (!isset($response) && !empty($value)) {
            $response = \GuzzleHttp\Psr7\parse_response($value);
        } elseif (!empty($this->cache) && !empty($response)) {
            $this->cache->set($cacheKey, \GuzzleHttp\Psr7\str($response), !empty($this->ttl) ? time() + $this->ttl : 0);
        }

        return !empty($response) ? $response : false;
    }
}
