<?php

namespace ElaborateCode\RowBloom\Interpolators;

enum Interpolator: string
{
    case Twig = TwigInterpolator::class;

    public static function getDefault(): static
    {
        return self::Twig;
    }
}
