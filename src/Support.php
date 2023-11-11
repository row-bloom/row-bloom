<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\DataLoaders\Contract as DataLoadersContract;
use RowBloom\RowBloom\DataLoaders\FsContract;
use RowBloom\RowBloom\Interpolators\Contract as InterpolatorsContract;
use RowBloom\RowBloom\Renderers\Contract as RenderersContract;
use RowBloom\RowBloom\Utils\ValidateDriverConcern;

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
    private array $fileExtensionDriversMap = [];

    /** @var array<string, int> */
    private array $folderDriversList = [];

    // ? add map extension => user fav driver (checked before $fileExtensionDriversMap)

    // --------------------------------------------

    public function registerDataLoaderDriver(string $driverName, string $className): static
    {
        $this->validateContract($className, DataLoadersContract::class);

        $this->DataLoaderDrivers[$driverName] = $className;

        foreach ((class_implements($className) ?: []) as $contract) {
            match ($contract) {
                FsContract::class => $this->registerFsDataLoaderDriver($className),
                default => null,
            };
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

        if (is_null($className::getFolderSupport())) {
            unset($this->folderDriversList[$className]);
        }

        foreach ($className::getSupportedFileExtensions() as $fileExtension => $priority) {
            unset($this->fileExtensionDriversMap[$fileExtension][$className]);

            if (count($this->fileExtensionDriversMap[$fileExtension]) === 0) {
                unset($this->fileExtensionDriversMap[$fileExtension]);
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
        return array_fill_keys(array_keys($this->fileExtensionDriversMap), true);
    }

    public function getFileExtensionDataLoaderDriver(string $extension): ?string
    {
        if (! array_key_exists($extension, $this->fileExtensionDriversMap)) {
            return null;
        }

        return array_key_last($this->fileExtensionDriversMap[$extension]);
    }

    public function getFolderDataLoaderDriver(): ?string
    {
        if (count($this->folderDriversList) === 0) {
            return null;
        }

        return array_key_last($this->folderDriversList);
    }

    private function registerFsDataLoaderDriver(string $className): void
    {
        /** @var string $fileExtension */
        foreach ($className::getSupportedFileExtensions() as $fileExtension => $priority) {
            if (! array_key_exists($fileExtension, $this->fileExtensionDriversMap)) {
                $this->fileExtensionDriversMap[$fileExtension] = [$className => $priority];
            }

            $this->fileExtensionDriversMap[$fileExtension][$className] = $priority;
            asort($this->fileExtensionDriversMap[$fileExtension]);
        }

        if (is_null($className::getFolderSupport())) {
            return;
        }

        $this->folderDriversList[$className] = $className::getFolderSupport();
        asort($this->folderDriversList);
    }

    // --------------------------------------------

    public function registerInterpolatorDriver(string $driverName, string $className): static
    {
        $this->validateContract($className, InterpolatorsContract::class);

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
        $this->validateContract($className, RenderersContract::class);

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
