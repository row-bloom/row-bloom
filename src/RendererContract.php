<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

interface RendererContract
{
    public function get(): string;

    public function save(File $file): bool;

    // ? stream()

    public function render(InterpolatedTemplate $template, Css $css, Options $options): static;
}
