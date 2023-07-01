<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Html;

interface RendererContract
{
    public function get(): string;

    public function save(File $file): bool;

    // ? echo()

    public function render(Html $template, Css $css, Options $options): static;
}
