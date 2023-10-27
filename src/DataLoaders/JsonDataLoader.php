<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\Drivers\DataLoaderContract;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\RowBloomException;
use RowBloom\RowBloom\Types\Table;

class JsonDataLoader implements DataLoaderContract
{
    public const NAME = 'JSON';

    public function getTable(File|string $file): Table
    {
        // ! make() blocks using mocked instance in container
        $file = $file instanceof File ? $file : app()->make(File::class, ['path' => $file]);

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
}