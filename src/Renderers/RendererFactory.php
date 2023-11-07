<?php

namespace RowBloom\RowBloom\Renderers;

use Illuminate\Container\Container;
use RowBloom\RowBloom\BaseDriverFactory;

final class RendererFactory extends BaseDriverFactory
{
    public function make(string $driver): RendererContract
    {
        $className = $driver;

        if (! class_exists($driver) && $this->support->hasRendererDriver($driver)) {
            $className = $this->support->getRendererDriver($driver);
        }

        $this->validateContract($className, RendererContract::class);

        return $this->container->get($className);
    }
}
