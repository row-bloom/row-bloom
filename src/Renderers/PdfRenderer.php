<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;

// ? MpdfRenderer
class PdfRenderer implements RendererContract
{
    public function render(array $template, string $css, array $config = []): mixed
    {
        return '';
    }
}
