<?php

namespace ElaborateCode\RowBloom\DataCollectors;

use ElaborateCode\RowBloom\DcContract;

class DcFactory
{
    public function __construct(
        protected string $defaultDriver = 'spreadsheet'
    ) {
    }

    public function make(?string $driver = null): DcContract
    {
        $driver ??= $this->defaultDriver;
        $driver = ucfirst(strtolower($driver));

        $class = __NAMESPACE__."\\{$driver}s\\{$driver}Dc";

        return new $class;
    }
}
