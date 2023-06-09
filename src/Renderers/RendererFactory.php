<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;

class RendererFactory
{
    // TODO
    public function make(): RendererContract
    {
        return new HtmlRenderer;
    }
}
