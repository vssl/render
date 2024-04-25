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
        if (!empty($this->config['cache'])
            && !$this->config['cache'] instanceof CacheAdapterInterface
        ) {
            throw new ResolverException(
                'Cache must implement \Journey\Cache\CacheAdapterInterface.'
            );
        }

        $this->setAuthHeaders();
        $this->setSitePasswordHeader();
        $this->api = new PageApi($request, $this->config);
    }

    /**
     * If we have an auth token in cookies, then add that to the request headers
     * for all API calls.
     *
     * @return array
     */
    public function setAuthHeaders()
    {
        $authToken = $_COOKIE['vssl-token'] ?? null;
        $authExpiry = $_COOKIE['vssl-token-exp'] ?? null;

        $this->config['isAuthenticated'] = !empty($authToken)
            && !empty($authExpiry)
            && time() < $authExpiry;

        if ($this->config['isAuthenticated']) {
            $this->config['headers'] = array_merge(
                $this->config['headers'] ?? [],
                ['Authorization' => "Bearer $authToken"]
            );
        }
    }

    /**
     * If we have a cookie for a password-protected site, then add that to the
     * request headers for all API calls.
     *
     * @return array
     */
    public function setSitePasswordHeader()
    {
        $sitePassword = $_COOKIE['vssl-site-pw'] ?? null;

        if (!empty($sitePassword)) {
            $this->config['headers'] = array_merge(
                $this->config['headers'] ?? [],
                ['Sites-Organization-Password' => $sitePassword]
            );
        }
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
     * Get the configuration settings.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get the configured CacheAdapterInterface.
     *
     * @return \Journey\Cache\CacheAdapterInterface
     */
    public function getCache()
    {
        return $this->config['cache'];
    }

    /**
     * Resolve the current page from Vessel Pages. Returns a renderable page or
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
                'page' => (is_array($page) && $status == 200)
                    ? new Renderer($this->config, $page)
                    : $page,
                'metadata' => (is_array($page) && $status == 200)
                    ? new Metadata($this->config, $page)
                    : $page,
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
            $config = array_merge(
                [
                    'cache' => null,
                    'cache_ttl' => false,
                    'base_uri' => 'https://api.vssl.io',
                    'base_path' => '/api/v2',
                    'required_fields' => [
                        'id',
                        'title',
                        'stripes'
                    ],
                    // 'templates' => 'your-directory'
                ],
                $assign
            );
        }
        return $config ?: [];
    }
}
