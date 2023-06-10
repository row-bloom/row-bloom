<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

interface RendererContract
{
    // TODO: config should be an object with getter
    public function __construct(InterpolatedTemplate $template, Css $css, array $config = []);

    public function getRendering(): mixed;

    public function save(File $file): bool;

    // public function stream(): void;
}
