<?php

namespace RowBloom\RowBloom\Drivers;

use RowBloom\RowBloom\Types\Table;

interface DataCollectorContract
{
    public function getTable(string $path): Table;

    /**
     * Associative array 'extension' => true
     */
    public static function getSupportedFileExtensions(): array;
}
