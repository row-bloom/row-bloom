<?php

namespace ElaborateCode\RowBloom\Fs;

class File
{
    public function __construct(protected string $path)
    {
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
        return is_file($this->path);
    }

    public function isWritable(): bool
    {
        return is_writable($this->path);
    }

    public function dir(): string
    {
        return dirname($this->path);
    }

    public function extension(): string
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    public function readFileContent(): ?string
    {
        if (! $this->exists() || $this->isDir()) {
            return null;
        }

        return file_get_contents($this->path);
    }

    public function touch(): bool
    {
        return touch($this->path);
    }

    public function mustExist(): static
    {
        if (! $this->exists()) {
            throw new FsException("{$this->path} path does not exist");
        }

        return $this;
    }

    public function mustNotExist(): static
    {
        if ($this->exists()) {
            throw new FsException("{$this->path} path does exist");
        }

        return $this;
    }

    public function mustBeDir(): static
    {
        if (! $this->isDir()) {
            throw new FsException("{$this->path} is not a directory");
        }

        return $this;
    }

    public function mustBeFile(): static
    {
        if (! $this->isDir()) {
            throw new FsException("{$this->path} is not a regular file");
        }

        return $this;
    }

    public function mustBeWritable(): static
    {
        if (! $this->isWritable()) {
            throw new FsException("{$this->path} is not writable");
        }

        return $this;
    }

    public function mustBeExtension(string $extension): static
    {
        // TODO: support array of extensions?
        if (strcmp(strtolower($this->extension()), strtolower($extension)) !== 0) {
            throw new FsException("{$this->path} is not writable");
        }

        return $this;
    }

    // delete()

    public function startSaving(): WriteStream
    {
        if (! file_exists($this->dir())) {
            if (! mkdir($this->dir(), 0777, true)) {
                throw new FsException(sprintf('Could not create the directory %s.', $this->dir()));
            }
        }

        // TODO: handle override file
        if ($this->exists()) {
            if (! $this->isWritable()) {
                throw new FsException(sprintf('The file %s is not writable.', $this->path));
            }
        } else {
            if (! $this->touch()) {
                throw new FsException(sprintf('The file %s could not be created.', $this->path));
            }
        }

        return new WriteStream(fopen($this->path, 'w'));
    }
}
