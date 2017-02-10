Vessel Render
-------------
[![Build Status](https://travis-ci.org/journeygroup/vessel-render.svg?branch=master)](https://travis-ci.org/journeygroup/vessel-render)
[![Code Coverage](https://scrutinizer-ci.com/g/journeygroup/vessel-render/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/journeygroup/vessel-render/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/journeygroup/vessel-render/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/journeygroup/vessel-render/?branch=master)

## What

This package contains a PHP rendering agent for Vessel pages. It is includes a
PSR-15 middleware implementation to easily attach to any conforming framework
and will automatically consume the Vessel Pages API and render production ready
markup.

## Configuration

```php
use Vessel\Resolver;
use Journey\Cache\LocalAdapter;

$config = [
    'cache' => new LocalAdapter('/tmp')
];

Resolver::config($config);

// for manual instantiation, you can also pass in a configuration.

$resolver = new Resolver($request, $config);
```
### Configuration options

The only required configuration option is an implementation of 
`Journey\Cache\CacheAdapterInterface`.

Option          | Description
----------------|---------------------------------------
cache           | Instance of `Journey\Cache\CacheAdapterInterface`
base_uri        | URI for Vessel API interaction
required_fields | Only considers API data valid if it contains these keys.
templates       | Additional directory to find custom render templates.

## How

Attach the [middleware](/src/Middleware.php) to your favorite PSR-15 dispatcher 
before your router. When the request arrives in your controller methods, it will
have an attribute `ws-page` with content ready to be rendered.

```php
<?php

namespace App\Controllers;

class YourController {
    /**
     * Any controller method.
     */
    public function anypage(ServerRequestInterface $request)
    {
        $page = $request->getAttribute('ws-page');
    }
}
```