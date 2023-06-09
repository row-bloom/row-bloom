<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;

class HtmlRenderer implements RendererContract
{
    public function render(array $template, string $css, array $config = []): string
    {
        return '<!DOCTYPE html><html><head>'
            . '<title>Row bloom</title>'
            . "<style>$css</style>"
            . '</head>'
            . '<body>' . implode('\n', $template) . '</body>'
            . '</html>';
    }
}
