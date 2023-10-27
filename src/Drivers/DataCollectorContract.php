<?php

namespace RowBloom\RowBloom\Drivers;

use RowBloom\RowBloom\Types\Table;

interface DataCollectorContract
{
    public function getTable(string $path): Table;

    /**
     * @return array<string, int>
     */
    public static function getSupportedFileExtensions(): array;
}
