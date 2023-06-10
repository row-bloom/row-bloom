<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

interface RendererContract
{
    // TODO: options should be an object with getters?
    public function __construct(InterpolatedTemplate $template, Css $css, array $options = []);

    public function getRendering(): mixed;

    public function save(File $file): bool;

    // public function stream(): void;
}
