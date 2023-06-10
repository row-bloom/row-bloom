<?php

namespace ElaborateCode\RowBloom\DataCollectors;

use ElaborateCode\RowBloom\DataCollectorContract;

class DataCollectorFactory
{
    public function __construct(
        protected string $defaultDriver = 'spreadsheet'
    ) {
    }

    public function make(?string $driver = null): DataCollectorContract
    {
        $driver ??= $this->defaultDriver;
        $driver = ucfirst(strtolower($driver));

        $class = __NAMESPACE__."\\{$driver}s\\{$driver}DataCollector";

        return new $class;
    }
}
