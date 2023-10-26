<?php

namespace RowBloom\RowBloom\Interpolators;

use RowBloom\RowBloom\Drivers\InterpolatorContract;
use RowBloom\RowBloom\RowBloomException;
use RowBloom\RowBloom\Support;

final class InterpolatorFactory
{
    public function __construct(private Support $support)
    {
    }

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
