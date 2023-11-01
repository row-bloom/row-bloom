<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Types\Table;

class FolderDataLoader implements DataLoaderContract
{
    public const NAME = 'Folder';

    public function __construct(
        private DataLoaderFactory $dataLoaderFactory,
        private ?Config $config = null
    ) {
    }

    public function getTable(File $file, Config $config = null): Table
    {
        $this->config = $config ?? $this->config;

        $file->mustExist()->mustBeReadable()->mustBeDir();

        $table = Table::fromArray([]);

        foreach ($file->ls() as $path) {
            $table->append(
                $this->dataLoaderFactory->makeFromPath($path)->getTable($path, $config)
            );
        }

        return $table;
    }

    public static function getSupportedFileExtensions(): array
    {
        return [];
    }
}
