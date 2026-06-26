<?php

namespace Tests;

use GuzzleHttp\Psr7\ServerRequest;
use Journey\Cache\Adapters\LocalAdapter;
use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use Vssl\Render\Renderer;
use Vssl\Render\RendererException;
use Vssl\Render\Resolver;

class RendererTest extends TestCase
{
    /**
     * Instance of our renderer
     *
     * @var \Vssl\Render\Renderer
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
    public function setUp(): void
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
        $this->assertIsString($output);
        $this->assertTrue((bool) preg_match("/vssl-stripe--header/", $output));
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
        $this->assertTrue((bool) preg_match("/3xnf}yxFwVHCsXR8p3BRBRZQi2/", $output));
        $this->assertTrue((bool) preg_match("/<a>Test<\/a>/", $output));
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

    /**
     * Test the render engine's ability to accept a 'templates' configuration item.
     *
     * @return void
     */
    public function testTemplateConfiguraton()
    {
        $config = Resolver::config();
        $config['templates'] = __DIR__ . "/templates";
        $data = $this->renderer->getData();
        $data['stripes'][] = [
            'type' => 'stripe-test',
        ];
        $renderer = new Renderer($config, $data);
        $output = (string) $renderer;
        $this->assertTrue((bool) preg_match("/3xnf}yxFwVHCsXR8p3BRBRZQi2/", $output));
        $this->assertTrue((bool) preg_match("/https:\/\/api.vssl.io\/images\/sepia\/123.jpg/", $output));
    }

    /**
     * Test the exception handling on templates that throw exceptions.
     *
     * @return void
     */
    public function testRenderException()
    {
        $this->renderer->registerTheme('test', __DIR__ . "/templates");
        $data = $this->renderer->getData();
        $data['stripes'][] = [
            'type' => 'stripe-exception',
        ];
        $this->renderer->setData($data);
        $this->assertEquals('Error rendering page: k+M8itwwby/pfbbKmz2', (string) $this->renderer);
    }

    /**
     * An embed stripe with an explicit height renders that height inline.
     *
     * @return void
     */
    public function testEmbedStripeWithExplicitHeight()
    {
        $data = $this->renderer->getData();
        $data['stripes'][] = [
            'type' => 'stripe-embed',
            'embed' => '<iframe style="width:100%; height:100%;" src="//example.com"></iframe>',
            'height' => 480,
        ];
        $this->renderer->setData($data);
        $output = (string) $this->renderer;
        $this->assertStringContainsString('style="height: 480px; padding-bottom: 0;"', $output);
    }

    /**
     * An embed stripe without a height renders no inline style (default sizing).
     *
     * @return void
     */
    public function testEmbedStripeWithoutHeight()
    {
        $data = $this->renderer->getData();
        $data['stripes'][] = [
            'type' => 'stripe-embed',
            'embed' => '<iframe style="width:100%; height:100%;" src="//example.com"></iframe>',
        ];
        $this->renderer->setData($data);
        $output = (string) $this->renderer;
        $this->assertStringContainsString('class="vssl-stripe--embed--content"', $output);
        $this->assertStringNotContainsString('padding-bottom: 0;', $output);
    }

    /**
     * A file stripe with custom link text uses it for the download button label
     * and aria-label.
     *
     * @return void
     */
    public function testFileStripeUsesCustomLinkText()
    {
        $data = $this->renderer->getData();
        $data['stripes'][] = [
            'type' => 'stripe-file',
            'file' => ['file' => 'report.pdf', 'filename' => 'report.pdf', 'id' => 1],
            'link_text' => 'Download the 2026 report',
        ];
        $this->renderer->setData($data);
        $output = (string) $this->renderer;
        $this->assertStringContainsString('>Download the 2026 report</a>', $output);
        $this->assertStringContainsString('aria-label="Download the 2026 report"', $output);
    }

    /**
     * A file stripe without custom link text falls back to "Download File".
     *
     * @return void
     */
    public function testFileStripeFallsBackToDefaultLinkText()
    {
        $data = $this->renderer->getData();
        $data['stripes'][] = [
            'type' => 'stripe-file',
            'file' => ['file' => 'report.pdf', 'filename' => 'report.pdf', 'id' => 1],
        ];
        $this->renderer->setData($data);
        $output = (string) $this->renderer;
        $this->assertStringContainsString('>Download File</a>', $output);
    }

    /**
     * A table stripe wraps its table in a keyboard-focusable scrollable region
     * so wide tables that overflow horizontally can be scrolled without a mouse.
     * When a caption is present the region is named by it via aria-labelledby.
     *
     * @return void
     */
    public function testTableStripeRendersKeyboardScrollableRegion()
    {
        $data = $this->renderer->getData();
        $data['stripes'][] = [
            'type' => 'stripe-table',
            'id' => 7,
            'caption' => ['html' => 'Course schedule'],
            'hasHeadersInFirstRow' => true,
            'dataset' => [['Course', 'Term'], ['Art', 'Spring']],
        ];
        $this->renderer->setData($data);
        $output = (string) $this->renderer;
        $this->assertStringContainsString('class="vssl-stripe--table--scroll-wrap"', $output);
        $this->assertStringContainsString('role="region"', $output);
        $this->assertStringContainsString('tabindex="0"', $output);
        $this->assertStringContainsString('aria-labelledby="vssl-stripe--table--caption--7"', $output);
        $this->assertStringContainsString('<caption id="vssl-stripe--table--caption--7"', $output);
    }

    /**
     * A table stripe without a caption falls back to a generic aria-label so the
     * scrollable region still has an accessible name.
     *
     * @return void
     */
    public function testTableStripeWithoutCaptionUsesAriaLabel()
    {
        $data = $this->renderer->getData();
        $data['stripes'][] = [
            'type' => 'stripe-table',
            'id' => 8,
            'hasHeadersInFirstRow' => true,
            'dataset' => [['Course', 'Term'], ['Art', 'Spring']],
        ];
        $this->renderer->setData($data);
        $output = (string) $this->renderer;
        $this->assertStringContainsString('class="vssl-stripe--table--scroll-wrap"', $output);
        $this->assertStringContainsString('aria-label="Table"', $output);
    }
}
