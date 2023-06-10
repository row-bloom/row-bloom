<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

class RendererFactory
{
    // TODO
    public function make(
        InterpolatedTemplate $template,
        Css $css,
        array $config = []
    ): RendererContract {
        return new HtmlRenderer($template, $css, $config);
    }
}
