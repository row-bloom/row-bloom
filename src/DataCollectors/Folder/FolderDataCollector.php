<?php

namespace RowBloom\RowBloom\DataCollectors\Folder;

use RowBloom\RowBloom\DataCollectorContract;
use RowBloom\RowBloom\DataCollectors\DataCollectorFactory;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Types\Table;

class FolderDataCollector implements DataCollectorContract
{
    public const NAME = 'Folder';

    public function getTable(File|string $file): Table
    {
        /** @var File */
        $file = $file instanceof File ? $file : app()->make(File::class, ['path' => $file]);

        $file->mustExist()->mustBeReadable()->mustBeDir();

        $table = Table::fromArray([]);

        foreach ($file->ls() as $path) {
            $table->append(
                app()->make(DataCollectorFactory::class)->makeFromPath($path)->getTable($path)
            );
        }

        return $table;
    }

    public static function getSupportedFileExtensions(): array
    {
        return [];
    }
}
