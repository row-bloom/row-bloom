<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\RowBloomException;
use RowBloom\RowBloom\Types\Table;
use RowBloom\RowBloom\Types\TableLocation;

class FolderDataLoader implements FsContract, RecursiveContract
{
    public const NAME = 'Folder';

    protected Factory $factory;

    public function __construct(protected ?Config $config = null)
    {
    }

    public function getTable(TableLocation $tableLocation, Config $config = null): Table
    {
        if (! isset($this->factory)) {
            throw new RowBloomException('Use setFactory to set '.Factory::class.' on: '.static::class);
        }

        $this->config = $config ?? $this->config;

        $file = $tableLocation->getFile();
        $file->mustExist()->mustBeReadable()->mustBeDir();

        $table = Table::fromArray([]);

        foreach ($file->ls() as $path) {
            $path = TableLocation::make($path);

            $table->append(
                $this->factory->makeFromLocation($path)->getTable($path, $config)
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

    public function setFactory(Factory $factory): static
    {
        $this->factory = $factory;

        return $this;
    }
}
