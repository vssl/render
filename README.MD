Vessel Render
-------------
[![Build Status](https://travis-ci.org/journeygroup/vssl-render.svg?branch=master)](https://travis-ci.org/journeygroup/vssl-render)
[![Code Coverage](https://scrutinizer-ci.com/g/journeygroup/vssl-render/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/journeygroup/vssl-render/?branch=master)

## What

This package contains a PHP rendering agent for Vessel Pages. It includes a
PSR-15 middleware implementation to easily attach to any conforming framework
and will automatically consume the Vessel Pages API and render production-ready
markup.

## Configuration

```php
use Vssl\Resolver;
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

Option            | Description
------------------|---------------------------------------
`cache`           | Instance of `Journey\Cache\CacheAdapterInterface` (or `null` to disable)
`cache_ttl`       | Integer, number of seconds to store in cache
`base_uri`        | URI for Vessel API interaction
`base_path`       | Path to add to `base_uri` for versioned Vessel API interaction
`required_fields` | Keys required for API data to be considered valid
`templates`       | Additional directory to find custom render templates

## How

Attach the [middleware](/src/Middleware.php) to your favorite PSR-15 dispatcher 
before your router. When the request arrives in your controller methods, it will
have an attribute `vssl-page` with content ready to be rendered.

```php
<?php

namespace App\Controllers;

class YourController {
    /**
     * Any controller method.
     */
    public function anypage(ServerRequestInterface $request)
    {
        $page = $request->getAttribute('vssl-page');
    }
}
```

## Developing against a local checkout

Themes running in Docker can use a local checkout of this repo in place of the release in their `composer.lock`. The
checkout is mounted read-only outside the theme, and [`bin/link-render`](/bin/link-render) symlinks it into the
theme’s `vendor/vssl/render` on container start and after every `composer install`/`update`. Composer only ever
replaces the symlink, so it can’t modify your files, and nothing in the theme folder on your Mac is touched, so
`composer install` there (for your editor) keeps working. Each time it links, it reports how the checkout compares to
the theme’s locked version:

```
vssl/render: linked ~/repos/render (main @ 6.9.2), matches locked 6.9.2
vssl/render: linked ~/repos/render (main @ 6.9.2-3-gabc1234), 3 unreleased commit(s) on top of locked 6.9.2
vssl/render: WARNING: linked ~/repos/render (main @ 6.9.3) is newer than 6.9.2, which this project’s composer.lock expects …
```

To set it up in a theme, add this to its service in `docker-compose.yml`:

```yaml
    volumes:
      # …the theme’s existing volumes
      # Optional local vssl/render checkout from VSSL_RENDER_PATH, mounted read-only outside the project and symlinked
      # into vendor/ on start (see docker/supervisord.conf), so Composer can’t modify it. A wrong path fails
      # `docker compose up` instead of mounting an empty folder. When unset, Compose can’t skip the mount, so the
      # project is mounted at an unused path instead.
      - type: bind
        source: ${VSSL_RENDER_PATH:-.}
        target: /var/vssl-render-unused${VSSL_RENDER_PATH:+/../vssl-render}
        read_only: true
        bind:
          create_host_path: false
    environment:
      VSSL_RENDER_PATH: ${VSSL_RENDER_PATH:-} # host path, shown in link-render’s messages
```

Add this one-shot program to the theme’s supervisord config (rebuild the image afterwards). It also puts the locked
release back when `VSSL_RENDER_PATH` is unset:

```ini
[program:link-render]
command=sh -c 'if [ -x /var/vssl-render/bin/link-render ]; then /var/vssl-render/bin/link-render; else [ ! -e /var/vssl-render/.git ] || echo "vssl/render: WARNING: $VSSL_RENDER_PATH has no bin/link-render (it predates it), so it isn’t linked" >&2; [ ! -L vendor/vssl/render ] || { rm vendor/vssl/render && composer install --no-interaction; }; fi'
directory=/var/www
priority=1
autorestart=false
startsecs=0
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
```

And these hooks to the theme’s `composer.json`, which do nothing outside the container:

```json
"scripts": {
    "post-install-cmd": "[ ! -x /var/vssl-render/bin/link-render ] || /var/vssl-render/bin/link-render",
    "post-update-cmd": "[ ! -x /var/vssl-render/bin/link-render ] || /var/vssl-render/bin/link-render"
}
```

Then set `VSSL_RENDER_PATH` in the theme’s `.env` (e.g. `VSSL_RENDER_PATH=~/repos/render`) and run
`docker compose up -d`. Unset it and run `docker compose up -d` again to go back to the locked release. Messages show
in `docker compose logs` and after `composer install`/`update` in the container.

To run a theme against an older version than your main checkout, give it its own clone checked out at that version:

```bash
git clone --no-hardlinks ~/repos/render ~/repos/render-6.8 && git -C ~/repos/render-6.8 checkout 6.8.21
```

Use `--no-hardlinks` (or clone from GitHub): a plain local clone shares git object files with the original through
hard links, and Docker Desktop then can’t read them in either container (`Permission denied`), so the version
comparison fails. A `git worktree` doesn’t work either, because its git metadata lives outside the mount. Checkouts
from before `bin/link-render` existed can’t be linked; cherry-pick the script onto that branch, or use the locked
release.
