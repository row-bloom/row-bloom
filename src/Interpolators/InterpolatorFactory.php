<?php

namespace RowBloom\RowBloom\Interpolators;

use RowBloom\RowBloom\Drivers\BaseDriverFactory;
use RowBloom\RowBloom\Drivers\InterpolatorContract;
use RowBloom\RowBloom\RowBloomException;

final class InterpolatorFactory extends BaseDriverFactory
{
    public function make(string $driver): InterpolatorContract
    {
        $className = $driver;

        if (! class_exists($driver) && $this->support->hasInterpolatorDriver($driver)) {
            $className = $this->support->getInterpolatorDriver($driver);
        }

        if (! is_a($className, InterpolatorContract::class, true)) {
            throw new RowBloomException("'{$driver}' is not a valid interpolator");
        }

        return app()->make($className);
    }
}
