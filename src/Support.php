<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\DataCollectors\DataCollector;
use RowBloom\RowBloom\Interpolators\Interpolator;

class Support
{
    private array $dataCollectorDrivers = [];

    private array $interpolatorDrivers = [];

    /** @var array<string, string> */
    private array $rendererDrivers = [];

    private array $supportedTableFileExtensions = [];

    public function __construct()
    {
        $this->setDataCollectorDrivers()
            ->setSupportedTableFileExtensions()
            ->setInterpolatorDrivers();
    }

    public function setDataCollectorDrivers(): static
    {
        foreach (DataCollector::cases() as $dataCollector) {
            $className = $dataCollector->value;

            if (! class_exists($className)) {
                continue;
            }

            $this->dataCollectorDrivers[$dataCollector->name] = $className;
        }

        return $this;
    }

    public function getDataCollectorDrivers(): array
    {
        return $this->dataCollectorDrivers;
    }

    public function setInterpolatorDrivers(): static
    {
        foreach (Interpolator::cases() as $interpolator) {
            $className = $interpolator->value;

            if (! class_exists($className)) {
                continue;
            }

            $this->interpolatorDrivers[$interpolator->name] = $className;
        }

        return $this;
    }

    public function getInterpolatorDrivers(): array
    {
        return $this->interpolatorDrivers;
    }

    // --------------------------------------------

    public function registerRendererDriver(string $driverName, string $className): static
    {
        $this->rendererDrivers[$driverName] = $className;

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

    public function setSupportedTableFileExtensions(): static
    {
        foreach ($this->dataCollectorDrivers as $className) {
            $this->supportedTableFileExtensions += $className::getSupportedFileExtensions();
        }

        return $this;
    }

    /**
     * @return array An associative array that contains supported extensions as keys, all values are set to true
     */
    public function getSupportedTableFileExtensions(): array
    {
        return $this->supportedTableFileExtensions;
    }

    /**
     * @return ?array An associative array of 'optionName' => \<bool\> or null if $renderer is invalid
     */
    public function getRendererOptionsSupport(string $renderer): ?array
    {
        if (! $this->hasRendererDriver($renderer)) {
            return null;
        }

        return $this->getRendererDriver($renderer)::getOptionsSupport();
    }
}
