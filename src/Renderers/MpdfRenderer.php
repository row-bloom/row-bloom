<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

class MpdfRenderer implements RendererContract
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
        // ...
        return $this;
    }

    public function getRendering(): mixed
    {
        return $this->rendering;
    }

    public function save(File $file): bool
    {
        // ...
        return false;
    }
}
