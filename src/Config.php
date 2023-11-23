<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\Renderers\Css\BaseCss;

class Config
{
    public function __construct(
        protected array $driverConfigs = [],
        public ?BaseCss $baseCss = null,
        public bool $mergeCssToHeaderFooter = false,
    ) {
    }

    public function setDriverConfig(object $config): static
    {
        $this->driverConfigs[get_class($config)] = $config;

        return $this;
    }

    /**
     * @template TClassName of object
     *
     * @param  class-string<TClassName>  $driverConfigName
     * @return ?TClassName
     */
    public function getDriverConfig(string $driverConfigName): ?object
    {
        return $this->driverConfigs[$driverConfigName] ?? null;
    }

    /** @param  class-string  $driverConfigName */
    public function tapDriverConfig(string $driverConfigName, callable $callback): static
    {
        if (array_key_exists($driverConfigName, $this->driverConfigs)) {
            $callback($this->driverConfigs[$driverConfigName]);

            return $this;
        }

        return $this;
    }
}
