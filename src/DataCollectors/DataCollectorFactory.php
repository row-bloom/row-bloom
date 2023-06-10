<?php

namespace ElaborateCode\RowBloom\DataCollectors;

use ElaborateCode\RowBloom\DataCollectorContract;

class DataCollectorFactory
{
    const DEFAULT_DRIVER = 'spreadsheet';

    // TODO: recheck logic
    public static function make(?string $driver = null): DataCollectorContract
    {
        $driver ??= static::DEFAULT_DRIVER;
        $driver = ucfirst(strtolower($driver));

        $class = __NAMESPACE__."\\{$driver}s\\{$driver}DataCollector";

        return new $class;
    }
}
