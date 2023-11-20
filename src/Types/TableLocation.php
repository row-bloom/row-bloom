<?php

namespace RowBloom\RowBloom\Types;

use RowBloom\RowBloom\Fs\File;

class TableLocation
{
    public static function make(string $value): static
    {
        return new static($value);
    }

    final public function __construct(
        public readonly string $value,
        public readonly ?string $driver = null,
    ) {
    }

    public function isFileLocation(): bool
    {
        return true;
    }

    public function toFile(): File
    {
        return File::fromPath($this->value);
    }
}
