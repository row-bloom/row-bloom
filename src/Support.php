<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\DataCollectors\DataCollector;
use ElaborateCode\RowBloom\Interpolators\Interpolator;
use ElaborateCode\RowBloom\Renderers\Renderer;

class Support
{
    private array $dataCollectorDrivers = [];

    private array $interpolatorDrivers = [];

    private array $rendererDrivers = [];

    private array $supportedTableFileExtensions = [];

    public function __construct()
    {
        $this->setDataCollectorDrivers()
            ->setSupportedTableFileExtensions()
            ->setInterpolatorDrivers()
            ->setRendererDrivers();
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

    public function setRendererDrivers(): static
    {
        foreach (Renderer::cases() as $renderer) {
            $className = $renderer->value;

            if (! class_exists($className)) {
                continue;
            }

            $this->rendererDrivers[$renderer->name] = $className;
        }

        return $this;
    }

    public function getRendererDrivers(): array
    {
        return $this->rendererDrivers;
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
    public function getRendererOptionsSupport(Renderer|string $renderer): ?array
    {
        $className = $renderer instanceof Renderer ? $renderer->value : $renderer;

        if (! class_exists($className) || ! is_a($className, RendererContract::class, true)) {
            return null;
        }

        return $className::getOptionsSupport();
    }
}
