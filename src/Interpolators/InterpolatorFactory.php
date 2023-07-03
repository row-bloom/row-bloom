<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;
use Exception;

final class InterpolatorFactory
{
    public function make(Interpolator|string $driver): InterpolatorContract
    {
        if ($driver instanceof Interpolator) {
            return new $driver->value;
        }

        if (is_a($driver, InterpolatorContract::class, true)) {
            return new $driver;
        }

        throw new Exception("'{$driver}' is not a valid interpolator");
    }
}
