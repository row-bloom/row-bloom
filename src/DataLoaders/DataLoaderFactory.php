<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\BaseDriverFactory;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\RowBloomException;
use RowBloom\RowBloom\Types\TableLocation;

final class DataLoaderFactory extends BaseDriverFactory
{
    public function make(string $driver): DataLoaderContract
    {
        $className = $driver;

        if (! class_exists($driver) && $this->support->hasDataLoaderDriver($driver)) {
            $className = $this->support->getDataLoaderDriver($driver);
        }

        $this->validateContract($className, DataLoaderContract::class);

        return app()->make($className);
    }

    // TODO: make it possible to distinguish Location required capabilities (http, file IO...)
    public function makeFromLocation(TableLocation $tableLocation): DataLoaderContract
    {
        if(!is_null($tableLocation->driver)) {
            return $this->make($tableLocation->driver);
        }

        $file = $tableLocation->getFile();

        $driver = match (true) {
            $file->exists() => $this->resolveFsDriver($file),
            default => throw new RowBloomException("Couldn't resolve a driver for the path '{$file}'"),
        };

        return $this->make($driver);
    }

    private function resolveFsDriver(File $file): string
    {
        if ($file->isDir()) {
            return $this->support->getDataLoaderDriver(FolderDataLoader::NAME);
        }

        return $this->support->getFileExtensionDataLoaderDriver($file->extension()) ??
            throw new RowBloomException("Couldn't resolve a driver for the file '{$file}'");
    }
}
