<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\Drivers\DataLoaderContract;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Types\Table;

class FolderDataLoader implements DataLoaderContract
{
    public const NAME = 'Folder';

    public function getTable(File $file): Table
    {
        $file->mustExist()->mustBeReadable()->mustBeDir();

        $table = Table::fromArray([]);

        foreach ($file->ls() as $path) {
            $table->append(
                app()->make(DataLoaderFactory::class)->makeFromPath($path)->getTable($path)
            );
        }

        return $table;
    }

    public static function getSupportedFileExtensions(): array
    {
        return [];
    }
}
