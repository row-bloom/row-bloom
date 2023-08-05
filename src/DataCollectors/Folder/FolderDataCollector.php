<?php

namespace ElaborateCode\RowBloom\DataCollectors\Folder;

use ElaborateCode\RowBloom\DataCollectorContract;
use ElaborateCode\RowBloom\DataCollectors\DataCollectorFactory;
use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Types\Table;

class FolderDataCollector implements DataCollectorContract
{
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
