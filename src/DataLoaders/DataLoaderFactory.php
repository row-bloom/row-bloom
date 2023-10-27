<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\Drivers\BaseDriverFactory;
use RowBloom\RowBloom\Drivers\DataLoaderContract;
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

    public function makeFromPath(string $path): DataLoaderContract
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
            return $this->support->getDataLoaderDriver(FolderDataLoader::NAME);
        }

        return $this->support->getFileExtensionDataLoaderDriver($file->extension()) ??
            throw new RowBloomException("Couldn't resolve a driver for the file '{$file}'");
    }
}
