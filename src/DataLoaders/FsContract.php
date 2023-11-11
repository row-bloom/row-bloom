<?php

namespace RowBloom\RowBloom\DataLoaders;

interface FsContract extends Contract
{
    /**
     * @return array<string, int>
     * - Key: file extension
     * - Value: priority (in case another driver supports the same extension)
     */
    public static function getSupportedFileExtensions(): array;

    public static function getFolderSupport(): ?int;
}
