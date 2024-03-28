<?php

namespace Vssl\Render;

use DOMDocument;
use DOMXPath;
use League\Plates\Engine;

class Renderer
{
    /**
     * Renderer configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * Page data to render.
     *
     * @var array
     */
    protected $data;

    /**
     * Cache adapter.
     *
     * @var \Journey\Cache\CacheAdapterIterface
     */
    protected $cache;

    /**
     * String data to output.
     *
     * @var string
     */
    protected $output;

    /**
     * Template engine.
     *
     * @var \League\Plates\Engine
     */
    protected $engine;

    /**
     * Fields that are required in order to render.
     *
     * @var array
     */
    protected $required;

    /**
     * Name of the current theme.
     *
     * @var string
     */
    protected $theme;

    /**
     * Initialize our new renderer.
     */
    public function __construct(array $config, array $data)
    {
        $this->config = $config;
        $this->required = array_filter($config['required_fields']);
        $this->setData($data);
        $this->engine = new Engine(__DIR__ . "/../templates");
        $this->registerFunctions();
        if (isset($config['templates'])) {
            $this->registerTheme('default', $config['templates']);
        }
    }

    /**
     * Register custom theme functions particular function.
     *
     * @return $this
     */
    public function registerFunctions()
    {
        $this->engine->registerFunction('wrapperClasses', [$this, 'wrapperClasses']);
        $this->engine->registerFunction('image', [$this, 'image']);
        $this->engine->registerFunction('file', [$this, 'file']);
        $this->engine->registerFunction('inline', [$this, 'inline']);
        $this->engine->registerFunction('inlineJson', [$this, 'inlineJson']);
        $this->engine->registerFunction('tableOfContents', [$this, 'tableOfContents']);
    }

    /**
     * Add a single directory to the template engine.
     *
     * @param  string $theme
     * @param  string $directory directory of templates
     * @return $this
     */
    public function registerTheme($theme, $directory)
    {
        if (!is_dir($directory)) {
            throw new RendererException('The specified theme directory does not exist: ' . $directory);
        }
        $this->theme = $theme;
        $this->engine->addFolder($theme, $directory, true);
        return $this;
    }

    /**
     * Returns the template rendering engine.
     *
     * @return \League\Plates\Engine
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Get the page data that will be rendered.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Replace the page data
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->validateData($data);
        $this->data = $data;
        return $this;
    }

    /**
     * Validate that the page data is complete enough to render.
     *
     * Note: Throws an exception if the page data is incomplete.
     *
     * @param  array  $data array
     * @return boolean
     */
    public function validateData(array $data)
    {
        if (!count(array_diff_key(array_flip($this->required), $data))) {
            return true;
        }
        throw new RendererException('Invalid or incomplete page data');
    }

    /**
     * Generate wrapper classes for stripes
     *
     * @return string
     */
    public function wrapperClasses($string)
    {
        $type = str_replace("stripe-", "", $string);
        return 'vssl-stripe vssl-stripe--' . $type;
    }

    /**
     * Returns the image url on the vssl server.
     *
     * @param  string $name      hash.extension
     * @param  string $style     image style name
     * @return string
     */
    public function image($name, $style = false)
    {
        return rtrim($this->config['base_uri'], '/') . "/images" . ($style ? '/' . $style : '') . "/" . $name;
    }

    /**
     * Returns the file url on the vssl server.
     *
     * @param  string $name      hash.extension
     * @return string
     */
    public function file($name)
    {
        return $name['file'];
    }

    /**
     * Strip most tags from output stored by the inline editor.
     *
     * @param  string $str           Output
     * @param  string $allowed_tags  Permitted HTML tags
     * @return string
     */
    public function inline($str, $allowed_tags = '<a><b><strong><i><em>')
    {
        return strip_tags($str, $allowed_tags);
    }

    /**
     * Converts an array into inline JSON. e.g. use in data-attrs
     *
     * @param  array $values
     * @return string
     */
    public function inlineJson($values)
    {
        return htmlspecialchars(json_encode($values), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Returns a table of contents based on the contents of the page
     *
     * @return string
     */
    public function tableOfContents()
    {
        if (empty($this->data['stripes'])) return '';

        $stripes = $this->data['stripes'];
        $filtered = array_filter($stripes, function ($s) {
            return ($s['type'] === 'stripe-break' && !empty($s['heading']['html']))
                || ($s['type'] === 'stripe-textblock' && !empty($s['content']['html']));
        });

        $items = array_map(function ($s) {
            switch ($s['type']) {
                case 'stripe-break':
                    $text = $s['heading']['html'];
                    return [[
                        'level' => 1,
                        'text' => strip_tags($text)
                    ]];
                case 'stripe-textblock':
                    $dom = new DOMDocument();
                    $dom->loadHTML($s['content']['html']);
                    $xpath = new DOMXPath($dom);
                    $headerList = $xpath->query('//h1 | //h2');
                    $headerArray = iterator_to_array($headerList);
                    return array_map(fn($n) => [
                        'level' => $n->tagName === 'h1' ? 1 : 2,
                        'text' => $n->nodeValue
                    ], $headerArray);
                default:
                    return NULL;
            }
        }, $filtered);

        $flatItems = array_merge(...array_filter($items, fn($i) => $i !== NULL));

        return $flatItems;
    }

    /**
     * Process data before its output. This is the last chance to make changes
     * to data before being passed to the actual template files.
     *
     * @return array
     */
    public function processData($data)
    {
        $data['themePrefix'] = $this->theme ? $this->theme . "::" : "";
        if (isset($data['stripes'])) {
            $data['stripes'] = array_map(function ($stripe) {
                $hook = 'processStripe' . ucwords(preg_replace("/^stripe\-/", '', $stripe['type']));
                return is_callable([$this, $hook])
                    ? $this->$hook($stripe)
                    : $stripe;
            }, array_filter($data['stripes'], function ($stripe) use ($data) {
                return $this->engine->exists($data['themePrefix'] . 'stripes/' . $stripe['type']);
            }));
        }
        return $data;
    }

    /**
     * Process stripe-googlemaps data. (implements preprocessStripeHook)
     *
     * @param  array $stripe array of data
     * @return array
     */
    public function processStripeGooglemap($stripe)
    {
        $stripe['location'] = (!empty($stripe['location'])
            ? $stripe['location']
            : '');

        $stripe['address'] = (!empty($stripe['formatted_address'])
            ? $stripe['formatted_address']
            : $stripe['location']);

        if (!empty($stripe['address'])) {
          $stripe['navigationUrl'] = 'https://www.google.com/maps?mapclient=embed&daddr=' . rawurlencode($stripe['address']);
          $stripe['largerUrl'] = 'https://maps.google.com/maps/place/' . rawurlencode($stripe['address']);
        }

        if (empty($stripe['zoom'])) {
          $stripe['zoom'] = (empty($stripe['address']) ? 8 : 15);
        }

        return $stripe;
    }

    /**
     * Render the current data.
     *
     * @return string
     */
    public function render()
    {
        return $this->engine->render('page', $this->processData($this->data));
    }

    /**
     * Return the rendered page.
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (\Exception $e) {
            return "Error rendering page: " . $e->getMessage();
        }
    }
}
