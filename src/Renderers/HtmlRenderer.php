<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

class HtmlRenderer implements RendererContract
{
    protected string $rendering;

    public function __construct(
        protected InterpolatedTemplate $template,
        protected Css $css,
        protected array $config = []
    ) {
        $this->render();
    }

    protected function render(): static
    {
        $this->rendering = '<!DOCTYPE html><html><head>'
            . '<title>Row bloom</title>'
            . "<style>{$this->css}</style>"
            . '</head>'
            . '<body>' . implode('\n', $this->template->toArray()) . '</body>'
            . '</html>';

        return $this;
    }

    public function getRendering(): mixed
    {
        return $this->rendering;
    }
}
