<?php

namespace RowBloom\RowBloom\Interpolators;

use RowBloom\RowBloom\BaseDriverFactory;

final class Factory extends BaseDriverFactory
{
    public function make(string $driver): Contract
    {
        $className = $driver;

        if (! class_exists($driver) && $this->support->hasInterpolatorDriver($driver)) {
            $className = $this->support->getInterpolatorDriver($driver);
        }

        $this->validateContract($className, Contract::class);

        return new $className;
    }
}
