<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\Drivers\DataCollectorContract;
use RowBloom\RowBloom\Drivers\InterpolatorContract;
use RowBloom\RowBloom\Drivers\RendererContract;
use RowBloom\RowBloom\Drivers\ValidateDriverConcern;

class Support
{
    use ValidateDriverConcern;

    /** @var array<string, string> */
    private array $dataCollectorDrivers = [];

    /** @var array<string, string> */
    private array $interpolatorDrivers = [];

    /** @var array<string, string> */
    private array $rendererDrivers = [];

    // ? improve extension - driver link shape
    /** @var array<string, true> */
    private array $supportedTableFileExtensions = [];

    // --------------------------------------------

    public function registerDataCollectorDriver(string $driverName, string $className): static
    {
        $this->validateContract($className, DataCollectorContract::class);

        $this->dataCollectorDrivers[$driverName] = $className;

        $this->supportedTableFileExtensions += $className::getSupportedFileExtensions();

        return $this;
    }

    public function removeDataCollectorDriver(string $driverName): static
    {
        if ($this->hasDataCollectorDriver($driverName)) {
            unset($this->dataCollectorDrivers[$driverName]);
        }

        $this->supportedTableFileExtensions = [];

        foreach ($this->dataCollectorDrivers as $className) {
            $this->supportedTableFileExtensions += $className::getSupportedFileExtensions();
        }

        return $this;
    }

    public function hasDataCollectorDriver(string $driverName): bool
    {
        return array_key_exists($driverName, $this->dataCollectorDrivers);
    }

    public function getDataCollectorDrivers(): array
    {
        return $this->dataCollectorDrivers;
    }

    public function getDataCollectorDriver(string $driverName): ?string
    {
        return $this->dataCollectorDrivers[$driverName];
    }

    /**
     * @return array An associative array that contains supported extensions as keys, all values are set to true
     */
    public function getSupportedTableFileExtensions(): array
    {
        return $this->supportedTableFileExtensions;
    }

    // --------------------------------------------

    public function registerInterpolatorDriver(string $driverName, string $className): static
    {
        $this->validateContract($className, InterpolatorContract::class);

        $this->interpolatorDrivers[$driverName] = $className;

        return $this;
    }

    public function removeInterpolatorDriver(string $driverName): static
    {
        if ($this->hasInterpolatorDriver($driverName)) {
            unset($this->interpolatorDrivers[$driverName]);
        }

        return $this;
    }

    public function hasInterpolatorDriver(string $driverName): bool
    {
        return array_key_exists($driverName, $this->interpolatorDrivers);
    }

    public function getInterpolatorDrivers(): array
    {
        return $this->interpolatorDrivers;
    }

    public function getInterpolatorDriver(string $driverName): ?string
    {
        return $this->interpolatorDrivers[$driverName];
    }

    // --------------------------------------------

    public function registerRendererDriver(string $driverName, string $className): static
    {
        $this->validateContract($className, RendererContract::class);

        $this->rendererDrivers[$driverName] = $className;

        return $this;
    }

    public function removeRendererDriver(string $driverName): static
    {
        if ($this->hasRendererDriver($driverName)) {
            unset($this->rendererDrivers[$driverName]);
        }

        return $this;
    }

    public function hasRendererDriver(string $driverName): bool
    {
        return array_key_exists($driverName, $this->rendererDrivers);
    }

    public function getRendererDrivers(): array
    {
        return $this->rendererDrivers;
    }

    public function getRendererDriver(string $driverName): ?string
    {
        return $this->rendererDrivers[$driverName];
    }

    /**
     * @return ?array An associative array of 'optionName' => \<bool\>
     */
    public function getRendererOptionsSupport(string $renderer): ?array
    {
        if (! $this->hasRendererDriver($renderer)) {
            return [];
        }

        return $this->getRendererDriver($renderer)::getOptionsSupport();
    }

    // --------------------------------------------
}
