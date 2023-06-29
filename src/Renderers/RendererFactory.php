<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Utils\BasicSingletonConcern;
use Exception;

final class RendererFactory
{
    use BasicSingletonConcern;

    public function make(Renderer|string|null $driver = null): RendererContract
    {
        $driver ??= Renderer::getDefault();

        if ($driver instanceof Renderer) {
            return new $driver->value;
        }

        if (class_exists($driver) && in_array(RendererContract::class, class_implements($driver), true)) {
            return new $driver;
        }

        throw new Exception("'{$driver}' is not a valid renderer");
    }
}
