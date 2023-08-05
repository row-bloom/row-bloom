<?php

namespace ElaborateCode\RowBloom\DataCollectors;

use ElaborateCode\RowBloom\DataCollectorContract;
use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\RowBloomException;

// TODO: improve factory to allow user to register new drivers and what they support
final class DataCollectorFactory
{
    public function make(DataCollector|string $driver): DataCollectorContract
    {
        if ($driver instanceof DataCollector) {
            return app()->make($driver->value);
        }

        if (is_a($driver, DataCollectorContract::class, true)) {
            return app()->make($driver);
        }

        throw new RowBloomException("'{$driver}' is not a valid data collector");
    }

    public function makeFromPath(string $path): DataCollectorContract
    {
        /** @var File */
        $file = app()->make(File::class, ['path' => $path]);

        $driver = match (true) {
            $file->exists() => $this->resolveFsDriver($file),
            default => throw new RowBloomException("Couldn't resolve a driver for the path '{$path}'"),
        };

        return $this->make($driver);
    }

    private function resolveFsDriver(File $file): DataCollector
    {
        if ($file->isDir()) {
            return DataCollector::Folder;
        }

        if ($file->extension() === 'json') {
            return DataCollector::Json;
        }

        if (in_array($file->extension(), ['xlsx', 'xls', 'xml', 'ods', 'slk', 'gnumeric', 'html', 'csv'], true)) {
            return DataCollector::Spreadsheet;
        }

        throw new RowBloomException("Couldn't resolve a driver for the file '{$file}'");
    }
}
