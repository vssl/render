# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is **vssl/render**, a PHP 8.3+ rendering agent for Journey Group's Vessel Pages. It's a PSR-15 middleware implementation that consumes the Vessel Pages API and renders production-ready markup using the League Plates templating engine.

## Commands

### Testing
```bash
vendor/bin/phpunit
```

Run tests with coverage:
```bash
vendor/bin/phpunit --coverage-html build/logs/coverage/
```

### Code Style
```bash
vendor/bin/phpcs
```

The project follows Laravel coding standards with PSR-2 compliance. Line limit is 120 characters (absolute max 150).

### Installation
```bash
composer install
```

## Architecture

### Request Flow
1. **Middleware** (`src/Middleware.php`) - PSR-15 middleware entry point
   - Instantiates `Resolver` with the PSR-7 request
   - Adds `vssl-resolver` attribute to request for downstream consumption

2. **Resolver** (`src/Resolver.php`) - Core resolution logic
   - Manages configuration via static `config()` method
   - Handles authentication (`vssl-token` cookie) and site passwords (`vssl-site-pw` cookie)
   - Uses `PageApi` to fetch page data from Vessel API
   - Returns resolved page data including `Renderer` and `Metadata` instances
   - Resolution result structure:
     ```php
     [
       'status' => 200,
       'data' => [...],
       'page' => Renderer instance,
       'metadata' => Metadata instance,
       'type' => 'page-type'
     ]
     ```

3. **PageApi** (`src/PageApi.php`) - API communication layer
   - Uses Guzzle HTTP client
   - Handles caching via `Journey\Cache\CacheAdapterInterface`
   - Sends `X-Render-Host` header from original request
   - Methods: `getPage($path)`, `getPageById($id)`, `getPagesById($ids)`
   - Caching disabled for authenticated or password-protected requests

4. **Renderer** (`src/Renderer.php`) - Template rendering engine
   - Uses League Plates for PHP templates
   - Templates in `templates/` directory, organized by type under `templates/stripes/`
   - Custom theme templates via `templates` config option
   - Helper functions: `image()`, `file()`, `block()`, `inline()`, `inlineJson()`, `wrapperClasses()`
   - Stripe processing hooks: `processStripe{StripeName}()` methods (e.g., `processStripeGooglemap()`, `processStripeTable()`)
   - Main render methods: `render()` (full page), `renderStripes()` (stripes only)
   - Table of contents generation via `getTableOfContents()` from break and textblock stripes

5. **Metadata** (`src/Metadata.php`) - OpenGraph metadata generator
   - Generates og:title, og:description, og:image tags
   - Call `getTags()` for array or cast to string for HTML output

### Configuration

Required config: `cache` (CacheAdapterInterface or null to disable)

Optional config:
- `cache_ttl` - Seconds to cache responses
- `base_uri` - Vessel API base URI (default: https://api.vssl.io)
- `base_path` - API version path (default: /api/v2)
- `required_fields` - Required page fields (default: id, title, stripes)
- `templates` - Custom template directory path

### Stripes System

Stripes are content blocks rendered via templates. Each stripe type has:
1. A template file in `templates/stripes/stripe-{type}.php`
2. Optional preprocessing hook `processStripe{Type}()` in Renderer
3. Data structure from Vessel API

Available stripe types: attributes, break, byline, contact, cta, details, embed, file, gallery, googlemap, grid, header, header-video, infographic, label, lede, link, menu, pullquote, reference, related, table, text-image, textblock, toc, videoembed

Custom themes can override templates by registering a theme directory via `registerTheme()`.

### Table Stripe Processing

The table stripe has complex processing logic in `processStripeTable()`:
- Handles `colspans` array (x, y, span coordinates) for merged cells
- Supports `additionalHeaderRows` and `additionalHeaderColumns` for multi-level headers
- Creates `tableData` array with text, colspan, and isAdditionalHeader properties
- Respects `hasHeadersInFirstRow`, `hasHeadersInFirstColumn`, `hasAlternatingRows` flags

### Authentication & Security

- JWT authentication via `vssl-token` cookie (checks expiry from payload)
- Site password protection via `vssl-site-pw` cookie
- Both add headers to API requests and disable caching when present
- Cookies are checked in `setAuthHeaders()` and `setSitePasswordHeader()` methods

## Testing

Tests are in `tests/` directory with bootstrap file at `tests/bootstrap.php`. Test coverage includes:
- Unit tests for all core classes
- Mock request handler at `tests/Mocks/MockRequestHandler.php`
- Test templates in `tests/templates/stripes/`
- Local test server router at `tests/server/router.php`
