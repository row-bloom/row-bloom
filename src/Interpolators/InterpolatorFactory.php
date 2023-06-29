<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;
use ElaborateCode\RowBloom\Utils\BasicSingletonConcern;
use Exception;

final class InterpolatorFactory
{
    use BasicSingletonConcern;

    public static $defaultDriver = '*twig';

    public function make(?string $driver = null): InterpolatorContract
    {
        $driver ??= self::$defaultDriver;

        $interpolator = $this->resolveDriver($driver);

        if ($interpolator) {
            return new $interpolator;
        }

        if (class_exists($driver) && in_array(InterpolatorContract::class, class_implements($driver), true)) {
            return new $driver;
        }

        throw new Exception("'{$driver}' is not a valid interpolator");
    }

    private function resolveDriver(string $driver): ?string
    {
        return match ($driver) {
            '*twig' => TwigInterpolator::class,
            default => null,
        };
    }
}
