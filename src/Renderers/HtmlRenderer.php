<?php

namespace RowBloom\RowBloom\Renderers;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;

class HtmlRenderer implements Contract
{
    public const NAME = 'HTML';

    protected string $rendering;

    protected Html $html;

    protected Css $css;

    protected Options $options;

    public function __construct(protected ?Config $config = null)
    {
    }

    public function get(): string
    {
        return $this->rendering;
    }

    public function save(File $file): bool
    {
        return $file->mustBeExtension('html')
            ->startSaving()
            ->save($this->rendering);
    }

    public function render(Html $html, Css $css, Options $options, Config $config = null): static
    {
        $this->html = $html;
        $this->css = $css;
        $this->options = $options;
        $this->config = $config ?? $this->config;

        // TODO: handle options with CSS @page, @media...

        $this->rendering = <<<_HTML
            <!DOCTYPE html>
            <head>
                <style>
                    $this->css
                </style>
                <title>Row bloom</title>
            </head>
            <body>
                $this->html
            </body>
            </html>
        _HTML;

        return $this;
    }

    public static function getOptionsSupport(): array
    {
        return [
            'displayHeaderFooter' => false,
            'headerTemplate' => false,
            'footerTemplate' => false,
            'printBackground' => false,
            'preferCssPageSize' => false,
            'landscape' => false,
            'format' => false,
            'width' => false,
            'height' => false,
            'margin' => false,
            'metadataTitle' => false,
            'metadataAuthor' => false,
            'metadataCreator' => false,
            'metadataSubject' => false,
            'metadataKeywords' => false,
        ];
    }
}
