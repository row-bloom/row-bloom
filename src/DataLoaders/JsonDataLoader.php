<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\RowBloomException;
use RowBloom\RowBloom\Types\Table;
use RowBloom\RowBloom\Types\TableLocation;

class JsonDataLoader implements FsDataLoaderContract
{
    public const NAME = 'JSON';

    public function __construct(protected ?Config $config = null)
    {
    }

    public function getTable(TableLocation $tableLocation, Config $config = null): Table
    {
        $this->config = $config ?? $this->config;

        $file = $tableLocation->getFile();
        $file->mustExist()->mustBeReadable()->mustBeFile()->mustBeExtension('json');

        $data = json_decode($file->readFileContent(), true);

        if (! is_array($data)) {
            throw new RowBloomException("Invalid Json '{$file}'");
        }

        return Table::fromArray($data);
    }

    public static function getSupportedFileExtensions(): array
    {
        return [
            'json' => 100,
        ];
    }

    public static function getFolderSupport(): ?int
    {
        return null;
    }
}
