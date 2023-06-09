<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;

class InterpolatorFactory
{
    function make(): InterpolatorContract
    {
        return new TwigInterpolator;
    }
}
