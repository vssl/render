<?php

namespace Vessel\Render;

use GuzzleHttp\Client;
use Journey\Cache\Adapters\LocalAdapter;
use Journey\Cache\CacheAdapterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Resolver
{
    /**
     * The request object.
     *
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * Configuration array.
     *
     * @var array
     */
    protected $config;

    /**
     * The page api object.
     *
     * @var \Vessel\Render\PageApi
     */
    protected $api;

    /**
     * Initialize a new Resolver
     */
    public function __construct(ServerRequestInterface $request, $config = false)
    {
        $this->request = $request;
        $this->config = is_array($config) ? static::config($config) : static::config();
        if (!$this->config['cache'] instanceof CacheAdapterInterface) {
            throw new ResolverException('Cache must implement \Journey\Cache\CacheAdapterInterface.');
        }
        $this->api = new PageApi($request, $this->config);
    }

    /**
     * Get a modified request message.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request->withAttribute('ws-page', $this->resolve());
    }

    /**
     * Resolve the current page from WebStories.
     *
     * @return array
     */
    public function resolve()
    {
        if ($response = $this->api->getPage($this->request->getUri()->getPath())) {
            $data = $this->decodeBody($response);
            return [
                'status' => $response->getStatusCode(),
                'data' => $data,
                'page' => is_array($data) ? new Renderer($this->config, $data) : $data,
            ];
        }
        return false;
    }

    /**
     * Decode the body of a particular request.
     *
     * @return mixed
     */
    public function decodeBody(ResponseInterface $response)
    {
        $body = (string) $response->getBody();
        if ($response->getHeaderLine('Content-Type') == "application/json") {
            return json_decode($body, true);
        }
        return $body;
    }

    /**
     * Configure the resolver.
     *
     * @return void
     */
    public static function config($assign = false)
    {
        static $config;

        if (is_array($assign) || !$config) {
            $config = array_merge([
                'cache' => null,
                'base_uri' => 'https://www.webstories.com/',
                'required_fields' => [
                    'id',
                    'title',
                    'stripes'
                ],
                // 'templates' => 'your-directory'
            ], $assign);
        }
        return $config ?: [];
    }
}
