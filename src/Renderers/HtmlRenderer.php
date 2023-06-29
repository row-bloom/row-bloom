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

    protected Html $template;

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

    public function render(Html $template, Css $css, Options $options): static
    {
        $this->template = $template;
        $this->css = $css;
        $this->options = $options;

        $this->rendering = '<!DOCTYPE html><html><head>'
            .'<title>Row bloom</title>'
            ."<style>{$this->css}</style>"
            .'</head>'
            ."<body>{$this->template}</body>"
            .'</html>';

        return $this;
    }
}
