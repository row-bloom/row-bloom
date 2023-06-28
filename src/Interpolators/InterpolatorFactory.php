<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;
use Exception;

class InterpolatorFactory
{
    public static $defaultDriver = 'twig';

    public static function make(?string $driver = null): InterpolatorContract
    {
        $driver ??= self::$defaultDriver;

        $interpolator = self::resolveDriver($driver);

        if ($interpolator) {
            return new $interpolator;
        }

        if (class_exists($driver) && in_array(InterpolatorContract::class, class_implements($driver), true)) {
            return new $driver;
        }

        throw new Exception("'{$driver}' is not a valid interpolator");
    }

    private static function resolveDriver(string $driver): ?string
    {
        return match ($driver) {
            'twig' => TwigInterpolator::class,
            default => null,
        };
    }
}
