<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Types\Table;
use RowBloom\RowBloom\Types\TableLocation;

interface DataLoaderContract
{
    public function getTable(TableLocation $tableLocation, Config $config = null): Table;

    /**
     * @return array<string, int>
     * - Key: file extension
     * - Value: priority (in case another driver supports the same extension)
     */
    public static function getSupportedFileExtensions(): array;
}
