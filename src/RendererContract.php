<?php

namespace ElaborateCode\RowBloom;

interface RendererContract
{
    // decorate HTML inside PDF?
    // TODO: template should be an object
    // TODO: config should be an object with getter
    public function render(array $template, string $css, array $config = []): mixed;

    // ? maybe output interface
    // save
    // stream
}
