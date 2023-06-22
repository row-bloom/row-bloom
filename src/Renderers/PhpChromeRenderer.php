<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Options;
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

    protected InterpolatedTemplate $interpolatedTemplate;

    protected Css $css;

    protected Options $options;

    public function getRendering(InterpolatedTemplate $interpolatedTemplate, Css $css, Options $options): string
    {
        $this->interpolatedTemplate = $interpolatedTemplate;
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

        // TODO: use private logic for html rendering
        $rendering = (new HtmlRenderer())->getRendering($this->interpolatedTemplate, $this->css, $this->options);

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
