<?php

namespace ElaborateCode\RowBloom\Fs;

use Exception;

class File
{
    protected string $path;

    public function __construct(string $path)
    {
        // if (false === $realPath) {
        //     throw new Exception("Invalid path $path");
        // }

        $this->path = realpath($path) ?: $path;
    }

    public function exists(): bool
    {
        return file_exists($this->path);
    }

    public function isDir(): bool
    {
        return is_dir($this->path);
    }

    public function isFile(): bool
    {
        return !is_dir($this->path);
    }

    public function isWritable(): bool
    {
        return is_writable($this->path);
    }

    public function dir(): string
    {
        return dirname($this->path);
    }

    public function readFileContent(): ?string
    {
        if (!$this->exists() || $this->isDir()) {
            return null;
        }

        return file_get_contents($this->path);
    }

    public function touch(): bool
    {
        return touch($this->path);
    }

    // delete()

    // TODO: add must prefixed methods (mustBeFile, mustBeDir...) return this

    public function startSaving(): WriteStream
    {
        if (!file_exists($this->dir())) {
            dump([
                $this->dir(),
                $this->path
            ]);
            if (!mkdir($this->dir(), 0777, true)) {
                throw new Exception(sprintf('Could not create the directory %s.', $this->dir()));
            }
        }

        // ? override
        if ($this->exists()) {
            if (!$this->isWritable()) {
                throw new Exception(sprintf('The file %s is not writable.', $this->path));
            }
        } else {
            if (!$this->touch()) {
                throw new Exception(sprintf('The file %s could not be created.', $this->path));
            }
        }

        return new WriteStream(fopen($this->path, 'w'));
    }
}
