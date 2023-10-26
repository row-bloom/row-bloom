<?php

namespace RowBloom\RowBloom\Renderers;

use RowBloom\RowBloom\Drivers\RendererContract;
use RowBloom\RowBloom\RowBloomException;
use RowBloom\RowBloom\Support;

final class RendererFactory
{
    public function __construct(private Support $support)
    {
    }

    public function make(string $driver): RendererContract
    {
        $className = $driver;

        if (! class_exists($driver) && $this->support->hasRendererDriver($driver)) {
            $className = $this->support->getRendererDriver($driver);
        }

        if (! is_a($className, RendererContract::class, true)) {
            throw new RowBloomException("'{$driver}' is not a valid renderer");
        }

        return app()->make($className);
    }
}
