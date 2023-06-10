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
        array $options = []
    ): RendererContract {
        return new HtmlRenderer($template, $css, $options);
    }
}
