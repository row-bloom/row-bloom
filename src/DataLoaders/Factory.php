<?php

namespace RowBloom\RowBloom\DataLoaders;

use RowBloom\RowBloom\BaseDriverFactory;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\RowBloomException;
use RowBloom\RowBloom\Types\TableLocation;

class Factory extends BaseDriverFactory
{
    public function make(string $driver): Contract
    {
        $className = $driver;

        if (! class_exists($driver) && $this->support->hasDataLoaderDriver($driver)) {
            $className = $this->support->getDataLoaderDriver($driver);
        }

        $this->validateContract($className, Contract::class);

        $instance = new $className;

        return $instance instanceof RecursiveContract ?
            $instance->setFactory($this) :
            $instance;
    }

    public function makeFromLocation(TableLocation|string $tableLocation): Contract
    {
        $tableLocation = $tableLocation instanceof TableLocation ? $tableLocation :
            TableLocation::make($tableLocation);

        if (isset($tableLocation->driver)) {
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
            return $this->support->getFolderDataLoaderDriver() ??
                throw new RowBloomException("Couldn't resolve a driver for the Folder '{$file}'");
        }

        return $this->support->getFileExtensionDataLoaderDriver($file->extension()) ??
            throw new RowBloomException("Couldn't resolve a driver for the file '{$file}'");
    }
}
