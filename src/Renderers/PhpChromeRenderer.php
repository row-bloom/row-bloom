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

        $page->navigate('data:text/html,'.$this->html())
            ->waitForNavigation();

        $options = [
            'landscape' => true,             // default to false
            // 'paperWidth' => 6.0,              // defaults to 8.5 (must be a float, value in inches)
            // 'paperHeight' => 6.0,              // defaults to 11.0 (must be a float, value in inches)

            // 'marginTop' => 0.0,              // defaults to ~0.4 (must be a float, value in inches)
            // 'marginBottom' => 1.4,              // defaults to ~0.4 (must be a float, value in inches)
            // 'marginLeft' => 1.0,              // defaults to ~0.4 (must be a float, value in inches)
            // 'marginRight' => 1.0,              // defaults to ~0.4 (must be a float, value in inches)

            'printBackground' => true,             // default to false

            'preferCSSPageSize' => true,             // default to false (reads parameters directly from @page)

            'displayHeaderFooter' => true,             // default to false
            'headerTemplate' => ''.
            '<div class="header" style="font-size:10px">'.
                '<div class="date"></div> '.
                '<div class="title"></div> '.
                // '<div class="url"></div> '.
            '</div>',
            'footerTemplate' => '
            <div class="footer" style="font-size:10px">
                <span class="pageNumber"></span> of <span class="totalPages"></span>
            </div>',

            'scale' => 1.2,              // defaults to 1.0 (must be a float)
        ];

        // ! PHP Fatal error:  Uncaught HeadlessChromium\Exception\PdfFailed: Cannot make a PDF. Reason : -32000 - Printing failed in C:\Dev\row-bloom\vendor\chrome-php\chrome\src\PageUtils\PagePdf.php:119
        // happens when page sizings isn't correct

        // ! style="font-size:10px" needs to be specified on header and footer

        $this->rendering = $page->pdf($options)->getBase64();

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

    private function html(): string
    {
        $body = $this->getHtmlBody();

        return <<<_HTML
        <!DOCTYPE html>
        <head>
            <style>
                $this->css
            </style>
            <title>Row bloom</title>
        </head>
        <body>
            $body
        </body>
        </html>
        _HTML;
    }

    private function getHtmlBody(): string
    {
        if (is_null($this->options->perPage)) {
            return implode('', $this->interpolatedTemplate->toArray());
        }

        $body = '';
        foreach ($this->interpolatedTemplate->toArray() as $i => $t) {
            $body .= "\n{$t}";

            if (
                ($i + 1) % $this->options->perPage === 0 &&
                ($i + 1) !== count($this->interpolatedTemplate->toArray())
            ) {
                $body .= '<div style="page-break-before: always;"></div>';
            }
        }

        return $body;
    }
}
