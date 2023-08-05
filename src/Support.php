<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\DataCollectors\DataCollector;

class Support
{
    private array $supportedTableFileExtensions = [];

    public function __construct()
    {
        $this->setSupportedTableFileExtensions();
    }

    public function setSupportedTableFileExtensions(): void
    {
        foreach (DataCollector::cases() as $dataCollector) {
            $className = $dataCollector->value;
            if (! class_exists($className)) {
                continue;
            }

            $this->supportedTableFileExtensions += $className::getSupportedFileExtensions();
        }
    }

    public function getSupportedTableFileExtensions(): array
    {
        return $this->supportedTableFileExtensions;
    }
}
