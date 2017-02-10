<?php

namespace Tests;

use GuzzleHttp\Psr7\ServerRequest;
use Journey\Cache\Adapters\LocalAdapter;
use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use Vessel\Render\Renderer;
use Vessel\Render\RendererException;
use Vessel\Render\Resolver;

class RendererTest extends TestCase
{
    /**
     * Instance of our renderer
     *
     * @var \Vessel\Render\Renderer
     */
    protected $renderer;

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
        $this->renderer = new Renderer(
            Resolver::config(),
            $this->data['page']
        );
    }

    /**
     * Ensure that the middleware implements the PSR-15 pattern.
     *
     * @return void
     */
    public function testRenderer()
    {
        $output = (string) $this->renderer;
        $this->assertInternalType('string', $output);
        $this->assertTrue((boolean) preg_match("/ws-stripe--header/", $output));
    }

    /**
     * Test theme registration functionality.
     *
     * @return void
     */
    public function testThemeRegistration()
    {
        $this->renderer->registerTheme('test', __DIR__ . "/templates");
        $data = $this->renderer->getData();
        $data['stripes'][] = [
            'type' => 'stripe-test',
        ];
        $this->renderer->setData($data);
        $output = (string) $this->renderer;
        $this->assertTrue((boolean) preg_match("/3xnf}yxFwVHCsXR8p3BRBRZQi2/", $output));
    }

    /**
     * Test exception thrown when registering a bad directory.
     *
     * @return void
     */
    public function testThemeRegistrationFailure()
    {
        $this->expectException(RendererException::class);
        $this->renderer->registerTheme('fail', __DIR__ . "/templates-fail");
    }

    /**
     * Test the getEngine method.
     *
     * @return void
     */
    public function testGetEngine()
    {
        $this->assertInstanceOf(Engine::class, $this->renderer->getEngine());
    }

    /**
     * Test the data validation checker.
     *
     * @return void
     */
    public function testInvalidData()
    {
        $this->expectException(RendererException::class);
        $this->renderer->validateData([
            'id' => 1,
            'title' => 'hello',
        ]);
    }
}
