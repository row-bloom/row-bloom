<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;

class MpdfRenderer implements RendererContract
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
        // ...
        return $this;
    }

    public function getRendering(): mixed
    {
        return $this->rendering;
    }
}
