<?php

namespace RowBloom\RowBloom\Interpolators;

use Illuminate\Container\Container;
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

        return $this->container->get($className);
    }
}
