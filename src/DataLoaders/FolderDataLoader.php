<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Types\Table;
use RowBloom\RowBloom\Types\TableLocation;

class FolderDataLoader implements DataLoaderContract
{
    public const NAME = 'Folder';

    public function __construct(
        private DataLoaderFactory $dataLoaderFactory,
        private ?Config $config = null
    ) {
    }

    public function getTable(TableLocation $tableLocation, Config $config = null): Table
    {
        $this->config = $config ?? $this->config;

        $file = $tableLocation->getFile();
        $file->mustExist()->mustBeReadable()->mustBeDir();

        $table = Table::fromArray([]);

        foreach ($file->ls() as $path) {
            $path = TableLocation::make($path);

            $table->append(
                $this->dataLoaderFactory->makeFromLocation($path)->getTable($path, $config)
            );
        }

        return $table;
    }

    public static function getSupportedFileExtensions(): array
    {
        return [];
    }
}
