<?php

namespace RowBloom\RowBloom\Drivers;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;

interface RendererContract
{
    // ? keep rendering base64
    public function get(): string;

    public function save(File $file): bool;

    // ? echo()

    public function render(Html $template, Css $css, Options $options, Config $config): static;

    public static function getOptionsSupport(): array;
}
