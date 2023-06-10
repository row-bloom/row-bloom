<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;

class InterpolatorFactory
{
    public function make(): InterpolatorContract
    {
        return new TwigInterpolator;
    }
}
