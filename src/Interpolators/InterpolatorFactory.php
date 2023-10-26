<?php

namespace RowBloom\RowBloom\Interpolators;

use RowBloom\RowBloom\Drivers\BaseDriverFactory;
use RowBloom\RowBloom\Drivers\InterpolatorContract;

final class InterpolatorFactory extends BaseDriverFactory
{
    public function make(string $driver): InterpolatorContract
    {
        $className = $driver;

        if (! class_exists($driver) && $this->support->hasInterpolatorDriver($driver)) {
            $className = $this->support->getInterpolatorDriver($driver);
        }

        $this->validateContract($className, InterpolatorContract::class);

        return app()->make($className);
    }
}
