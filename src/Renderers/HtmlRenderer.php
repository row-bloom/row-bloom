<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Options;
use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Html;

class HtmlRenderer implements RendererContract
{
    protected string $rendering;

    protected Html $html;

    protected Css $css;

    protected Options $options;

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

    public function render(Html $html, Css $css, Options $options): static
    {
        $this->html = $html;
        $this->css = $css;
        $this->options = $options;

        // TODO: handle options with CSS @page,@media...

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
}
