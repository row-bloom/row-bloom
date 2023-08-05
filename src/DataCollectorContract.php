<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Types\Table;

interface DataCollectorContract
{
    public function getTable(string $path): Table;

    /**
     * Associative array 'extension' => true
     */
    public static function getSupportedFileExtensions(): array;
}
