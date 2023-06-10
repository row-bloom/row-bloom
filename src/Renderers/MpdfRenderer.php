<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

/**
 * https://mpdf.github.io/
 *
 * HTML to PDF
 *
 * Pros:
 * - PDF specific attributes
 *
 * Cons
 * - Weak HTML and CSS support
 * - No js
 */
class MpdfRenderer implements RendererContract
{
    protected string $rendering;

    public function __construct(
        protected InterpolatedTemplate $template,
        protected Css $css,
        protected array $options = []
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
