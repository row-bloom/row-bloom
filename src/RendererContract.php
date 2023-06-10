<?php

namespace ElaborateCode\RowBloom;

interface RendererContract
{
    // decorate HTML inside PDF?
    // TODO: template should be an object
    // TODO: config should be an object with getter
    public function __construct(array $template, string $css, array $config = []);

    public function render(): static;

    public function getRendering(): mixed;

    // public function save(): void;

    // public function stream(): void;
}
