<?php

namespace RowBloom\RowBloom\Interpolators;

use RowBloom\RowBloom\InterpolatorContract;
use RowBloom\RowBloom\RowBloomException;

final class InterpolatorFactory
{
    public function make(Interpolator|string $driver): InterpolatorContract
    {
        if ($driver instanceof Interpolator) {
            return app()->make($driver->value);
        }

        if (is_a($driver, InterpolatorContract::class, true)) {
            return app()->make($driver);
        }

        throw new RowBloomException("'{$driver}' is not a valid interpolator");
    }
}
