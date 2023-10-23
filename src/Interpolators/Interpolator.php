<?php

namespace RowBloom\RowBloom\Interpolators;

enum Interpolator: string
{
    case Twig = TwigInterpolator::class;
    case Php = PhpInterpolator::class;
}
