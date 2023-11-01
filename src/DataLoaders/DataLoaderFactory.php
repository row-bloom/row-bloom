<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\BaseDriverFactory;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\RowBloomException;

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

    public function makeFromPath(File|string $file): DataLoaderContract
    {
        $file = $file instanceof File ? $file : File::fromPath($file);

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
