<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;

class HtmlRenderer implements RendererContract
{
    protected string $rendering;

    public function __construct(
        protected array $template,
        protected string $css,
        protected array $config = []
    ) {
    }

    public function render(): static
    {
        $this->rendering = '<!DOCTYPE html><html><head>'
            . '<title>Row bloom</title>'
            . "<style>{$this->css}</style>"
            . '</head>'
            . '<body>' . implode('\n', $this->template) . '</body>'
            . '</html>';

        return $this;
    }

    public function getRendering(): mixed
    {
        return $this->rendering;
    }
}