<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Utils\BasicSingletonConcern;
use Exception;

final class RendererFactory
{
    use BasicSingletonConcern;

    public function make(Renderer|string $driver): RendererContract
    {
        if ($driver instanceof Renderer) {
            return new $driver->value;
        }

        if (is_a($driver, RendererContract::class, true)) {
            return new $driver;
        }

        throw new Exception("'{$driver}' is not a valid renderer");
    }
}
