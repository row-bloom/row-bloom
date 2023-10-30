<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Types\Table;

interface DataLoaderContract
{
    public function getTable(File $path): Table;

    /**
     * @return array<string, int>
     * - Key: file extension
     * - Value: priority (in case another driver supports the same extension)
     */
    public static function getSupportedFileExtensions(): array;
}
