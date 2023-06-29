<?php

namespace ElaborateCode\RowBloom\DataCollectors;

use ElaborateCode\RowBloom\DataCollectorContract;
use ElaborateCode\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;
use ElaborateCode\RowBloom\Fs\File;
use Exception;

class DataCollectorFactory
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance(): DataCollectorFactory
    {
        if (! self::$instance) {
            self::$instance = new DataCollectorFactory();
        }

        return self::$instance;
    }

    public function make(string $driver): DataCollectorContract
    {
        $dataCollector = $this->resolveDriver($driver);

        if ($dataCollector) {
            return new $dataCollector;
        }

        if (class_exists($driver) && in_array(DataCollectorContract::class, class_implements($driver), true)) {
            return new $driver;
        }

        throw new Exception("'{$driver}' is not a valid data collector");
    }

    public function makeFromPath(string $path): DataCollectorContract
    {
        $file = File::fromPath($path);

        $driver = match (true) {
            $file->exists() => $this->resolveFileDriver($file),
            default => throw new Exception("Couldn't resolve a driver for the path '{$path}'"),
        };

        return $this->make($driver);
    }

    private function resolveDriver(string $driver): ?string
    {
        return match ($driver) {
            '*spreadsheet' => SpreadsheetDataCollector::class,
            default => null,
        };
    }

    private function resolveFileDriver(File $file): string
    {
        if (in_array($file->extension(), ['xlsx', 'xls', 'xml', 'ods', 'slk', 'gnumeric', 'html', 'csv'], true)) {
            return '*spreadsheet';
        }

        throw new Exception("Couldn't resolve a driver for the file '{$file}'");
    }
}
