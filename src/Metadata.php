<?php

namespace Vssl\Render;

class Metadata
{
    /**
     * The render configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The page array data.
     *
     * @var array
     */
    protected $page;

    /**
     * List of all renderable tags.
     *
     * @var array
     */
    protected $tags = [
        'title',
        'description',
        'image'
    ];

    /**
     * Initialize our Metadata renderer.
     */
    public function __construct($config, $page)
    {
        $this->config = $config;
        $this->page = $page;
    }

    /**
     * Get the title metatag.
     *
     * @return string
     */
    public function title()
    {
        $title = empty($this->page['title']) ? null : htmlspecialchars($this->page['title'], ENT_QUOTES);
        return $title
            ? '<meta property="og:title" content="' . $title . '" />'
            : null;
    }

    /**
     * Get the description metatag.
     *
     * @return string
     */
    public function description()
    {
        $summary = empty($this->page['summary']) ? null : htmlspecialchars($this->page['summary'], ENT_QUOTES);
        return $summary
            ? '<meta property="og:description" content="' . $summary . '" />'
            : null;
    }

    /**
     * Get the image metatag.
     *
     * @return [type] [description]
     */
    public function image()
    {
        $image = empty($this->page['image'])
            ? null
            : ltrim($this->config['base_uri'], '/') . "/images/" . $this->page['image'];
        return $image
            ? '<meta property="og:image" content="' . $image . '" />'
            : null;
    }

    /**
     * Get all available tags.
     *
     * @return array
     */
    public function getTags()
    {
        return array_filter(array_map(function ($tag) {
            return $this->{$tag}();
        }, $this->tags));
    }


    /**
     * Generate the metadata.
     *
     * @return string
     */
    public function __toString()
    {
        return implode("\n", $this->getTags());
    }
}
