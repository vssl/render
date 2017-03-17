<?php

namespace Vssl\Render;

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
     * @var \Vssl\Render\PageApi
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
        return $this->request->withAttribute('vssl-page', $this->resolve());
    }

    /**
     * Get an instance of the PageApi class.
     *
     * @return \Vssl\Render\PageApi
     */
    public function getPageApi()
    {
        return $this->api;
    }

    /**
     * Resolve the current page from WebStories. Returns a render able page or
     * false.
     *
     * @return array
     */
    public function resolve()
    {
        $response = $this->api->getPage($this->request->getUri()->getPath());
        if ($response && ($page = $this->decodePage($response))) {
            $status = $response->getStatusCode();
            return [
                'status' => $status,
                'data' => $page,
                'page' => (is_array($page) && $status == 200) ? new Renderer($this->config, $page) : $page,
                'metadata' => (is_array($page) && $status == 200) ? new Metadata($this->config, $page) : $page,
                'type' => !empty($page['type']) ? $page['type'] : false
            ];
        }
        return [
            'status' => false,
            'error' => 'An unknown error occurred',
            'data' => [],
            'type' => false
        ];
    }

    /**
     * Decode the body of a particular request.
     *
     * @return mixed
     */
    public function decodePage(ResponseInterface $response)
    {
        $body = (string) $response->getBody();
        if ($response->getHeaderLine('Content-Type') == "application/json") {
            $page = json_decode($body, true);
            return !empty($page['exists']) ? $page['page'] : false;
        }
        return false;
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
                'cache_ttl' => false,
                'base_uri' => 'https://pages.vssl.io/',
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
