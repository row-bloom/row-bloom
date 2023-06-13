<?php

namespace ElaborateCode\RowBloom\DataCollectors;

use ElaborateCode\RowBloom\DataCollectorContract;

class DataCollectorFactory
{
    // TODO: recheck logic
    public static function make(string $driver): DataCollectorContract
    {
        $driver = ucfirst(strtolower($driver));

        $class = __NAMESPACE__."\\{$driver}s\\{$driver}DataCollector";

        return new $class;
    }
}
