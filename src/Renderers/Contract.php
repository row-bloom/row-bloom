<?php

namespace RowBloom\RowBloom\Renderers;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;

interface Contract
{
    // ? keep rendering base64
    public function get(): string;

    public function save(File $file): bool;

    // ? echo()

    public function render(Html $template, Css $css, Options $options, Config $config = null): static;

    public static function getOptionsSupport(): array;
}
