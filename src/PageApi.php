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
        $this->ttl = $config['cache_ttl'];
        $this->cache = $config['cache'];
        $this->host = $request->getUri()->getHost();
        $this->http = new Client([
            'base_uri' => rtrim($config['base_uri'], "/") . "/" . trim($config['base_api_path'], "/"),
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
        return $this->call("get", "/pages?slug=" . $path);
    }

    /**
     * Get a particular page from the api.
     *
     * @param  integer $id  unique id of page to fetch data for.
     * @return array|false
     */
    public function getPageById($id)
    {
        return $this->call("get", "/pages?ids=" . $id);
    }

    /**
     * Get a set of pages from the api.
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
     * Call a particular api endpoint.
     * @param string $method
     * @param string $url
     * @return \Psr\Http\Message\ResponseInterface|false
     */
    public function call($method, $url)
    {
        $cacheKey = $this->host . '::' . strtoupper($method) . "::" . $url;
        try {
            if (!$value = $this->cache->get($cacheKey)) {
                $response = $this->http->request(strtoupper($method), $url);
            }
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } catch (ConnectException $e) {
            // Suppress network errors at this level.
        }
        if (!isset($response) && !empty($value)) {
            $response = \GuzzleHttp\Psr7\parse_response($value);
        } elseif ($this->ttl !== false && !empty($response)) {
            $this->cache->set($cacheKey, \GuzzleHttp\Psr7\str($response), $this->ttl ? time() + $this->ttl : 0);
        }
        return !empty($response) ? $response : false;
    }
}
