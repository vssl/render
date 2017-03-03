<?php

namespace Tests;

use GuzzleHttp\Psr7\ServerRequest;
use Journey\Cache\Adapters\LocalAdapter;
use PHPUnit\Framework\TestCase;
use Vssl\Render\Metadata;
use Vssl\Render\Resolver;

class MetadataTest extends TestCase
{
    /**
     * Instance of our metadata
     *
     * @var \Vssl\Render\Metadata
     */
    protected $metadata;

    /**
     * The mock api data.
     *
     * @var array
     */
    protected $data;

    /**
     * Initialize a resolver instance.
     */
    public function setUp()
    {
        $this->data = json_decode(file_get_contents(__DIR__ . "/server/assets/single.json"), true);
        $this->metadata = new Metadata(
            Resolver::config([
                'cache' => new LocalAdapter('/tmp'),
                'base_uri' => 'http://127.0.0.1:1349',
            ]),
            $this->data['page']
        );
    }

    /**
     * Test the rendering of og:title
     *
     * @return void
     */
    public function testTitle()
    {
        $this->assertEquals('<meta property="og:title" content="my story" />', $this->metadata->titleTag());
    }

    /**
     * Test the rendering of og:title
     *
     * @return void
     */
    public function testDescription()
    {
        $this->assertEquals(
            '<meta property="og:description" content="this is a testable summary" />',
            $this->metadata->descriptionTag()
        );
    }

    /**
     * Test the rendering of og:image
     *
     * @return void
     */
    public function testImage()
    {
        $this->assertEquals(
            '<meta property="og:image" content="http://127.0.0.1:1349/images/somrandomhash.jpg" />',
            $this->metadata->imageTag()
        );
    }

    /**
     * Test the functionality of getTags()
     *
     * @return void
     */
    public function testGetTags()
    {
        $tags = $this->metadata->getTags();
        $value = [$this->metadata->titleTag(), $this->metadata->descriptionTag(), $this->metadata->imageTag()];
        $this->assertEquals($value, $tags);
    }

    /**
     * Test the metadata's ability to act as a string via __toString().
     *
     * @return void
     */
    public function testToString()
    {
        $this->assertEquals(implode("\n", $this->metadata->getTags()), (string) $this->metadata);
    }
}
