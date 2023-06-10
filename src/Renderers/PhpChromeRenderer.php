<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;
use HeadlessChromium\BrowserFactory;

/**
 * requires ext-sockets
 *
 * https://github.com/chrome-php/chrome#print-as-pdf
 *
 * Chromium browser based converter
 *
 * Pros:
 * - Strong HTML and CSS support
 * - Js support
 *
 * Cons
 * - requires chromium
 * - Other PDF specific attributes...
 */
class PhpChromeRenderer implements RendererContract
{
    protected string $rendering;

    public function __construct(
        protected InterpolatedTemplate $template,
        protected Css $css,
        protected array $options = []
    ) {
        $this->render();
    }

    protected function render(): static
    {
        $browserFactory = new BrowserFactory();
        // Start a new browser and create a new page
        $browser = $browserFactory->createBrowser();
        $page = $browser->createPage();

        $rendering = new HtmlRenderer($this->template, $this->css);
        // ! use break-after: page; CSS for page break
        // TODO: apply options

        $page->navigate('data:text/html,'.$rendering->getRendering())
            ->waitForNavigation();

        $this->rendering = $page->pdf()->getBase64();

        $browser->close();

        return $this;
    }

    public function getRendering(): mixed
    {
        return $this->rendering;
    }

    public function save(File $file): bool
    {
        return $file->mustBeExtension('pdf')
            ->startSaving()
            ->streamFilterAppend('convert.base64-decode')
            ->save($this->rendering);
    }
}
