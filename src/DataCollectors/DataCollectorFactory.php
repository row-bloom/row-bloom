<?php

namespace RowBloom\RowBloom\DataCollectors;

use RowBloom\RowBloom\DataCollectors\Folder\FolderDataCollector;
use RowBloom\RowBloom\Drivers\BaseDriverFactory;
use RowBloom\RowBloom\Drivers\DataCollectorContract;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\RowBloomException;

final class DataCollectorFactory extends BaseDriverFactory
{
    public function make(string $driver): DataCollectorContract
    {
        $className = $driver;

        if (! class_exists($driver) && $this->support->hasDataCollectorDriver($driver)) {
            $className = $this->support->getDataCollectorDriver($driver);
        }

        $this->validateContract($className, DataCollectorContract::class);

        return app()->make($className);
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

    private function resolveFsDriver(File $file): string
    {
        if ($file->isDir()) {
            return $this->support->getDataCollectorDriver(FolderDataCollector::NAME);
        }

        return $this->support->getFileExtensionDataCollectorDriver($file->extension()) ??
            throw new RowBloomException("Couldn't resolve a driver for the file '{$file}'");
    }
}
