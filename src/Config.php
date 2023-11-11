<?php

namespace RowBloom\RowBloom;

// ? global chromePath
// ? validate when passed to RowBloom
class Config
{
    /** @param  array<class-string, object>  $driverConfigs */
    public function __construct(protected array $driverConfigs = [])
    {
    }

    public function setDriverConfig(object $config): static
    {
        $this->driverConfigs[get_class($config)] = $config;

        return $this;
    }

    /**
     * @template TClassName
     *
     * @phpstan-return ?object
     *
     * @param  class-string<TClassName>  $driverConfigName
     * @return ?TClassName
     */
    public function getDriverConfig($driverConfigName): ?object
    {
        return $this->driverConfigs[$driverConfigName] ?? null;
    }

    /** @param  class-string  $driverConfigName */
    public function mutateDriverConfig($driverConfigName, callable $callback): static
    {
        if (! array_key_exists($driverConfigName, $this->driverConfigs)) {
            return $this;
        }

        $callback($this->driverConfigs[$driverConfigName]);

        return $this;
    }
}
