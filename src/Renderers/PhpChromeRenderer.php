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

    protected InterpolatedTemplate $template;

    protected Css $css;

    protected array $options = [];

    public function getRendering(InterpolatedTemplate $template, Css $css, array $options = []): string
    {
        $this->template = $template;
        $this->css = $css;
        $this->options = $options;

        $this->render();

        return $this->rendering;
    }

    protected function render(): static
    {
        $browserFactory = new BrowserFactory();
        // Start a new browser and create a new page
        $browser = $browserFactory->createBrowser();
        $page = $browser->createPage();

        $rendering = (new HtmlRenderer())->getRendering($this->template, $this->css, $this->options);
        // ! use break-after: page; CSS for page break
        // TODO: apply options

        $page->navigate('data:text/html,'.$rendering)
            ->waitForNavigation();

        $this->rendering = $page->pdf()->getBase64();

        $browser->close();

        return $this;
    }

    public function save(File $file): bool
    {
        return $file->mustBeExtension('pdf')
            ->startSaving()
            ->streamFilterAppend('convert.base64-decode')
            ->save($this->rendering);
    }
}
