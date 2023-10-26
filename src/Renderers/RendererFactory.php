<?php

namespace RowBloom\RowBloom\Renderers;

use RowBloom\RowBloom\Drivers\BaseDriverFactory;
use RowBloom\RowBloom\Drivers\RendererContract;

final class RendererFactory extends BaseDriverFactory
{
    public function make(string $driver): RendererContract
    {
        $className = $driver;

        if (! class_exists($driver) && $this->support->hasRendererDriver($driver)) {
            $className = $this->support->getRendererDriver($driver);
        }

        $this->validateContract($className, RendererContract::class);

        return app()->make($className);
    }
}
