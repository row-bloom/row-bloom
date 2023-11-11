<?php

namespace RowBloom\RowBloom\Renderers;

use RowBloom\RowBloom\BaseDriverFactory;

final class Factory extends BaseDriverFactory
{
    public function make(string $driver): Contract
    {
        $className = $driver;

        if (! class_exists($driver) && $this->support->hasRendererDriver($driver)) {
            $className = $this->support->getRendererDriver($driver);
        }

        $this->validateContract($className, Contract::class);

        return new $className;
    }
}
