<?php

namespace RowBloom\RowBloom\Interpolators;

use RowBloom\RowBloom\BaseDriverFactory;

final class InterpolatorFactory extends BaseDriverFactory
{
    public function make(string $driver): InterpolatorContract
    {
        $className = $driver;

        if (! class_exists($driver) && $this->support->hasInterpolatorDriver($driver)) {
            $className = $this->support->getInterpolatorDriver($driver);
        }

        $this->validateContract($className, InterpolatorContract::class);

        return is_null($this->container) ? new $className : $this->container->get($className);
    }
}
