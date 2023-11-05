<?php

namespace RowBloom\RowBloom\DataLoaders;

interface FsDataLoaderContract extends DataLoaderContract
{
    /**
     * @return array<string, int>
     * - Key: file extension
     * - Value: priority (in case another driver supports the same extension)
     */
    public static function getSupportedFileExtensions(): array;

    public static function getFolderSupport(): ?int;
}
