<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

interface RendererContract
{
    // ? decorate HTML inside PDF
    // TODO: config should be an object with getter
    public function __construct(InterpolatedTemplate $template, string $css, array $config = []);

    public function render(): static;

    public function getRendering(): mixed;

    // public function save(): void;

    // public function stream(): void;
}
