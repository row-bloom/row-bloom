<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\Drivers\DataLoaderContract;
use RowBloom\RowBloom\Drivers\InterpolatorContract;
use RowBloom\RowBloom\Drivers\RendererContract;
use RowBloom\RowBloom\Drivers\ValidateDriverConcern;

class Support
{
    use ValidateDriverConcern;

    /** @var array<string, string> */
    private array $DataLoaderDrivers = [];

    /** @var array<string, string> */
    private array $interpolatorDrivers = [];

    /** @var array<string, string> */
    private array $rendererDrivers = [];

    /** @var array<string, array<string, int>> */
    private array $supportedTableFileExtensions = [];

    // ? add map extension => user fav driver (checked before $supportedTableFileExtensions)

    // --------------------------------------------

    public function registerDataLoaderDriver(string $driverName, string $className): static
    {
        $this->validateContract($className, DataLoaderContract::class);

        $this->DataLoaderDrivers[$driverName] = $className;

        /** @var string $fileExtension */
        foreach ($className::getSupportedFileExtensions() as $fileExtension => $priority) {
            if (! array_key_exists($fileExtension, $this->supportedTableFileExtensions)) {
                $this->supportedTableFileExtensions[$fileExtension] = [$className => $priority];
            }

            $this->supportedTableFileExtensions[$fileExtension][$className] = $priority;
            asort($this->supportedTableFileExtensions[$fileExtension]);
        }

        return $this;
    }

    public function removeDataLoaderDriver(string $driverName): static
    {
        if (! $this->hasDataLoaderDriver($driverName)) {
            return $this;
        }

        $className = $this->DataLoaderDrivers[$driverName];

        unset($this->DataLoaderDrivers[$driverName]);

        foreach ($className::getSupportedFileExtensions() as $fileExtension => $priority) {
            unset($this->supportedTableFileExtensions[$fileExtension][$className]);

            if (count($this->supportedTableFileExtensions[$fileExtension]) === 0) {
                unset($this->supportedTableFileExtensions[$fileExtension]);
            }
        }

        return $this;
    }

    public function hasDataLoaderDriver(string $driverName): bool
    {
        return array_key_exists($driverName, $this->DataLoaderDrivers);
    }

    public function getDataLoaderDrivers(): array
    {
        return $this->DataLoaderDrivers;
    }

    public function getDataLoaderDriver(string $driverName): ?string
    {
        return $this->DataLoaderDrivers[$driverName] ?? null;
    }

    /** @return array<string, true> */
    public function getSupportedTableFileExtensions(): array
    {
        return array_fill_keys(array_keys($this->supportedTableFileExtensions), true);
    }

    public function getFileExtensionDataLoaderDriver(string $extension): ?string
    {
        if (! array_key_exists($extension, $this->supportedTableFileExtensions)) {
            return null;
        }

        return array_key_last($this->supportedTableFileExtensions[$extension]);
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
        return $this->interpolatorDrivers[$driverName] ?? null;
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
        return $this->rendererDrivers[$driverName] ?? null;
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
