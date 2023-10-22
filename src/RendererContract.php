<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Html;

interface RendererContract
{
    // ? keep rendering base64
    public function get(): string;

    public function save(File $file): bool;

    // ? echo()

    public function render(Html $template, Css $css, Options $options, Config $config): static;

    public static function getOptionsSupport(): array;
}
