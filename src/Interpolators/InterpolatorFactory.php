<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;
use ElaborateCode\RowBloom\Utils\BasicSingletonConcern;
use Exception;

final class InterpolatorFactory
{
    use BasicSingletonConcern;

    public static $defaultDriver = Interpolator::Twig;

    public function make(Interpolator|string|null $driver = null): InterpolatorContract
    {
        $driver ??= self::$defaultDriver;

        if ($driver instanceof Interpolator) {
            $interpolator = $this->resolveDriver($driver);

            return new $interpolator;
        }

        if (class_exists($driver) && in_array(InterpolatorContract::class, class_implements($driver), true)) {
            return new $driver;
        }

        throw new Exception("'{$driver}' is not a valid interpolator");
    }

    private function resolveDriver(Interpolator $driver): string
    {
        return match ($driver) {
            Interpolator::Twig => TwigInterpolator::class,
        };
    }
}
