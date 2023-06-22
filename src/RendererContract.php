<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

interface RendererContract
{
    // TODO: render()->save|get()
    public function getRendering(InterpolatedTemplate $template, Css $css, Options $options): string;

    public function save(File $file): bool;

    // public function stream(): void;
}
