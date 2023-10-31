<?php

namespace RowBloom\RowBloom\Types;

final class TablePath
{
    public static function fromPath(string $path): static
    {
        return new self($path);
    }

    public function __construct(
        public readonly string $path,
        public readonly string $driver = null,
    ) {
    }
}
