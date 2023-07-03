<?php

namespace ElaborateCode\RowBloom\DataCollectors;

use ElaborateCode\RowBloom\DataCollectorContract;
use ElaborateCode\RowBloom\Fs\File;
use Exception;

final class DataCollectorFactory
{
    public function make(DataCollector|string $driver): DataCollectorContract
    {
        if ($driver instanceof DataCollector) {
            return ROW_BLOOM_CONTAINER->make($driver->value);
        }

        if (is_a($driver, DataCollectorContract::class, true)) {
            return ROW_BLOOM_CONTAINER->make($driver);
        }

        throw new Exception("'{$driver}' is not a valid data collector");
    }

    // TODO Support composition behavior for folders
    public function makeFromPath(string $path): DataCollectorContract
    {
        $file = ROW_BLOOM_CONTAINER->make(File::class, ['path' => $path]);

        $driver = match (true) {
            $file->exists() => $this->resolveFileDriver($file),
            default => throw new Exception("Couldn't resolve a driver for the path '{$path}'"),
        };

        return $this->make($driver);
    }

    private function resolveFileDriver(File $file): DataCollector
    {
        if (in_array($file->extension(), ['xlsx', 'xls', 'xml', 'ods', 'slk', 'gnumeric', 'html', 'csv'], true)) {
            return DataCollector::Spreadsheet;
        }

        throw new Exception("Couldn't resolve a driver for the file '{$file}'");
    }
}
