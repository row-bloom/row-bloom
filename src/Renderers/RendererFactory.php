<?php

namespace RowBloom\RowBloom\Renderers;

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

        // TODO: pass config and $this if recursive loader
        // ! get doesn't take params

        return is_null($this->container) ? new $className : $this->container->get($className);
    }
}
