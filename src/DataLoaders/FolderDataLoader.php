<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Types\Table;
use RowBloom\RowBloom\Types\TableLocation;

class FolderDataLoader extends RecursiveFsDataLoader
{
    public const NAME = 'Folder';

    public function getTable(TableLocation $tableLocation, Config $config = null): Table
    {
        $this->config = $config ?? $this->config;

        $file = $tableLocation->getFile();
        $file->mustExist()->mustBeReadable()->mustBeDir();

        $table = Table::fromArray([]);

        foreach ($file->ls() as $path) {
            $path = TableLocation::make($path);

            $table->append(
                $this->getFactory()->makeFromLocation($path)->getTable($path, $config)
            );
        }

        return $table;
    }

    public static function getSupportedFileExtensions(): array
    {
        return [];
    }

    public static function getFolderSupport(): ?int
    {
        return 100;
    }
}
