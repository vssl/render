<?php

namespace Vessel\Render;

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
     * @var array
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
    }

    /**
     * Register custom theme functions particular function.
     *
     * @return $this
     */
    public function registerFunctions()
    {
        $this->engine->registerFunction('wrapperClasses', [$this, 'wrapperClasses']);
    }

    /**
     * Add a single directory to the template engine.
     *
     * @param  string $directories directory of templates
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
     * @return League\Plates\Engine
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
        return 'ws-stripe--' . $type;
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
            $data['stripes'] = array_filter($data['stripes'], function ($stripe) use ($data) {
                return $this->engine->exists($data['themePrefix'] . 'stripes/' . $stripe['type']);
            });
        }
        return $data;
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
