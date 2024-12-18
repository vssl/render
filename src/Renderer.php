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
        $this->engine->registerFunction('block', [$this, 'block']);
        $this->engine->registerFunction('inline', [$this, 'inline']);
        $this->engine->registerFunction('inlineJson', [$this, 'inlineJson']);
        $this->engine->registerFunction('getTableOfContents', [$this, 'getTableOfContents']);
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
     * Strip most tags from output stored by the editor, but permit some block level.
     *
     * @param  string $str           Output
     * @param  string $allowed_tags  Permitted HTML tags
     * @return string
     */
    public function block(
        $str,
        $allowed_tags = '<p><div><ul><ol><li><a><b><strong><i><em>'
    ) {
        return !empty($str) ? strip_tags($str, $allowed_tags) : null;
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
        return !empty($str) ? strip_tags($str, $allowed_tags) : null;
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
     * Given a break stripe's content and index within the page, returns an array of list items for rendering in a table of contents
     *
     * @param  string $content the html in the textblock stripe as a string
     * @param  int    $index   the index of the stripe within the page
     * @return array
     *
     */
    private function getBreakStripeHeading($stripe)
    {
        if (empty($stripe['heading']['html'])) {
            return null;
        }

        $text = strip_tags($stripe['heading']['html']);
        if (empty($text)) {
            return null;
        }

        $heading = [
            'level' => 1,
            'headingText' => $text,
        ];
        if (!empty($stripe['heading_id'])) {
            $heading['id'] = $stripe['heading_id'];
        }
        return $heading;
    }

    /**
     * Given a textblock stripe's content and index within the page, returns an array of list items for rendering in a table of contents
     *
     * @param  string $content the html in the textblock stripe as a string
     * @param  int    $index   the index of the stripe within the page
     * @return array
     *
     */
    private function getTextblockStripeHeadings($stripe)
    {
        if (empty($stripe['content']['html'])) {
            return [];
        }

        $dom = new DOMDocument();
        $dom->loadHTML($stripe['content']['html']);
        $xpath = new DOMXPath($dom);
        $headings = $xpath->query('//h1 | //h2 | //h3 | //h4');

        $listItems = [];
        foreach ($headings as $heading) {
            $listItem = [
                'level' => $heading->tagName === 'h1' || $heading->tagName === 'h3'
                    ? 1
                    : 2,
                'headingText' => $heading->nodeValue,
            ];
            if (!empty($heading->id)) {
                $listItem['id'] = $heading->id;
            }

            array_push($listItems, $listItem);
        }
        return $listItems;
    }

    /**
     * Returns a table of contents based on the contents of the page
     *
     * @return string
     */
    public function getTableOfContents($scope = ['break', 'textblock'])
    {
        if (empty($this->data['stripes'])) {
            return null;
        }
        $scope = !empty($scope) ? $scope : [];

        $items = [];
        $stripes = $this->data['stripes'];
        $inScope = fn($type) => in_array($type, $scope);
        foreach ($stripes as $index => $stripe) {
            if ($stripe['type'] === 'stripe-break' && $inScope('break')) {
                array_push($items, $this->getBreakStripeHeading($stripe, $index));
            }

            if ($stripe['type'] === 'stripe-textblock' && $inScope('textblock')) {
                array_push($items, ...$this->getTextblockStripeHeadings($stripe));
            }
        };

        return array_filter($items);
    }

    /**
     * Process data before its output. This is the last chance to make changes
     * to data before being passed to the actual template files.
     *
     * @return array
     */
    public function processData($data)
    {
        $themePrefix = $this->theme ? $this->theme . "::" : "";
        $data['themePrefix'] = $themePrefix;

        if (isset($data['stripes'])) {
            // Filter out stripes that don't exist for the theme
            $filteredStripes = array_filter(
                $data['stripes'],
                fn($s) => $this->engine->exists($themePrefix . 'stripes/' . $s['type'])
            );

            // Get an array of indexes
            $indexes = array_keys($filteredStripes);

            // Process each stripe when any defined `processStripe[type]` functions
            $processedStripes = array_map(function ($stripe, $index) {
                $stripeName = str_replace('stripe-', '', $stripe['type']);
                $stripeName = ucwords($stripeName, '-');
                $stripeName = str_replace('-', '', $stripeName);
                $hook = "processStripe{$stripeName}";
                return is_callable([$this, $hook]) ? $this->$hook($stripe, $index) : $stripe;
            }, $filteredStripes, $indexes);

            $data['stripes'] = $processedStripes;
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
     * Process stripe-table data.
     *
     * @param  array $stripe array of data
     * @return array
     */
    public function processStripeTable($stripe)
    {
        $stripe['caption'] = !empty($stripe['caption']['html'])
            ? $this->inline($stripe['caption']["html"])
            : null;
        $stripe['hasHeadersInFirstRow'] = !empty($stripe['hasHeadersInFirstRow'])
            ? $stripe['hasHeadersInFirstRow']
            : false;
        $stripe['hasHeadersInFirstColumn'] = !empty($stripe['hasHeadersInFirstColumn'])
            ? $stripe['hasHeadersInFirstColumn']
            : false;
        $stripe['hasAlternatingRows'] = !empty($stripe['hasAlternatingRows'])
            ? $stripe['hasAlternatingRows']
            : false;

        $dataset = $stripe['dataset'] ?? [];
        // Ensure each item in the dataset is an array
        $dataset = !empty($dataset) && is_array($dataset) ? $dataset : [[]];

        // The colspans property is an array of objects with `x`, `y`, and `span` properties
        // (e.g. `[{ x: 0, y: 0, span: 2 }, { x: 1, y: 2, span: 4 }]`)
        $colspans = $stripe['colspans'] ?? [];

        // We make an array of arrays so that the span values can be accessed directly
        // (e.g. `$colspansMap[0][0] === 2`)
        $colspansMap = array_pad([], count($dataset), []);
        foreach ($colspans as $colspan) {
            $colspansMap[$colspan['y']][$colspan['x']] = $colspan['span'];
        }

        $additionalHeaderRows = is_array($stripe['additionalHeaderRows'] ?? null)
            ? $stripe['additionalHeaderRows']
            : [];
        $additionalHeaderColumns = is_array($stripe['additionalHeaderColumns'] ?? null)
            ? $stripe['additionalHeaderColumns']
            : [];

        // Create a new tableData array to hold references to the original dataset data, along with the associated `colspan` value
        $tableData = [];
        foreach ($dataset as $rowIndex => $row) {
            // Push a nested array for each row in the dataset
            array_push($tableData, []);
            if (!is_array($row)) {
                continue;
            }

            // This buffer will be used when a cell has a colspan value greater than 1
            $spanBuffer = 0;
            foreach ($row as $columnIndex => $text) {
                // If we have a buffer value, decrement and skip this row index ($text should be empty anyways)
                if ($spanBuffer) {
                    $spanBuffer--;
                    continue;
                }

                // Otherwise, get the colspan from our above map
                $colspan = $colspansMap[$rowIndex][$columnIndex] ?? 1;
                $isAddtionalHeader = in_array($rowIndex, $additionalHeaderRows)
                    || in_array($columnIndex, $additionalHeaderColumns);

                array_push($tableData[$rowIndex], [
                    'text' => $text,
                    'colspan' => $colspan,
                    'isAdditionalHeader' => $isAddtionalHeader
                ]);

                // Update the buffer (by default, colspan is 1 and so the buffer is 0)
                $spanBuffer = $colspan - 1;
            }
        }

        $stripe['tableData'] = array_filter($tableData);
        return $stripe;
    }

    /**
     * Process stripe-toc data.
     *
     * @param  array $stripe array of data
     * @return array
     */
    public function processStripeToc($stripe)
    {
        $allowedScopes = $stripe['allowed_scopes'] ?? ['break', 'textblock'];
        $scope = $stripe['scope'] ?? $allowedScopes ?? [];
        $stripe['toc'] = $this->getTableOfContents(array_intersect($allowedScopes, $scope));
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
     * Render the only the stripes from the current data.
     *
     * @return string
     */
    public function renderStripes()
    {
        return $this->engine->render('stripes', $this->processData($this->data));
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
