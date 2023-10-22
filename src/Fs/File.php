<?php

namespace ElaborateCode\RowBloom\Fs;

use Stringable;

class File implements Stringable
{
    protected string $path;

    final public function __construct(string $path, bool $real = false)
    {
        if (! $real) {
            $this->path = str_replace('/', DIRECTORY_SEPARATOR, str_replace('\\', DIRECTORY_SEPARATOR, $path));

            return;
        }

        $realPath = realpath($path);

        if ($realPath === false) {
            throw new FsException("Invalid path format {$path}");
        }

        $this->path = $realPath;
    }

    public function __toString(): string
    {
        return $this->path;
    }

    // ============================================================
    // IO
    // ============================================================

    public function ls(): array
    {
        if (! $this->exists()) {
            throw new FsException("Cannot find path {$this->path} because it does not exist.");
        }

        if ($this->isFile()) {
            return [realpath($this->path)];
        }

        $folderContent = scandir($this->path);

        if ($folderContent === false) {
            throw new FsException("Cannot scan folder content of {$this->path}.");
        }

        return array_map(
            fn ($f) => $this->path.'/'.$f,
            array_diff($folderContent, ['..', '.'])
        );
    }

    public function readFileContent(): ?string
    {
        if (! $this->exists() || $this->isDir()) {
            return null;
        }

        return file_get_contents($this->path) ?: null;
    }

    public function startSaving(): WriteStream
    {
        // ? use realpath
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

    // ? delete()

    // ============================================================
    //
    // ============================================================

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

    public function isReadable(): bool
    {
        return is_readable($this->path);
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
        return strtolower(pathinfo($this->path, PATHINFO_EXTENSION));
    }

    public function touch(): bool
    {
        return touch($this->path);
    }

    // ============================================================
    // Must be...
    // ============================================================

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
        if (! $this->isFile()) {
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

    public function mustBeReadable(): static
    {
        if (! $this->isReadable()) {
            throw new FsException("{$this->path} is not readable");
        }

        return $this;
    }

    public function mustBeExtension(string $extension): static
    {
        // ? support array of extensions
        if (strcmp(strtolower($this->extension()), strtolower($extension)) !== 0) {
            throw new FsException("'{$this->path}' must be '{$extension}'");
        }

        return $this;
    }
}
