<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;

class InterpolatorFactory
{
    public static function make(string $driver): InterpolatorContract
    {
        return new TwigInterpolator;
    }
}
