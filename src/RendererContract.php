<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

interface RendererContract
{
    // TODO: options should be an object with getters?
    public function getRendering(InterpolatedTemplate $template, Css $css, ?Options $options = null): string;

    public function save(File $file): bool;

    // public function stream(): void;
}
