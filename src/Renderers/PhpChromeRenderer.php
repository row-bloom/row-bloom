<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;
use HeadlessChromium\BrowserFactory;

/**
 * requires ext-sockets
 * renders to base64. check https://base64.guru/converter/decode/pdf
 */
class PhpChromeRenderer implements RendererContract
{
    protected string $rendering;

    public function __construct(
        protected InterpolatedTemplate $template,
        protected Css $css,
        protected array $config = []
    ) {
        $this->render();
    }

    public function render(): static
    {
        $browserFactory = new BrowserFactory();
        // Start a new browser and create a new page
        $browser = $browserFactory->createBrowser();
        $page = $browser->createPage();

        $page->navigate('data:text/html,' . (new HtmlRenderer($this->template, $this->css))->getRendering())
            ->waitForNavigation();

        $this->rendering = $page->pdf()->saveToFile()->getBase64();

        $browser->close();

        return $this;
    }

    public function getRendering(): mixed
    {
        return $this->rendering;
    }

    // * save() look at HeadlessChromium\PageUtils\AbstractBinaryInput::saveToFile
}
