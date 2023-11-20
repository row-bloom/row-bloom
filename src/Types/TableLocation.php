<?php

namespace RowBloom\RowBloom\Types;

use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\RowBloomException;

class TableLocation
{
    public readonly string $url;

    public readonly string $scheme;

    public readonly string $path;

    public static function make(string $url): static
    {
        return new static($url);
    }

    final public function __construct(string $url, public readonly ?string $driver = null)
    {
        $filePath = realpath($url);

        if ($filePath !== false) {
            $this->scheme = 'file';
            $this->path = $filePath;

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $this->url = 'file:///'.$filePath;
            } else {
                $this->url = 'file://'.$filePath;
            }

            return;
        }

        $parsedUrl = parse_url($url);

        if (! is_array($parsedUrl) || ! isset($parsedUrl['scheme']) || ! isset($parsedUrl['path'])) {
            throw new RowBloomException($url.' has no valid URL scheme and is not a valid absolute file path');
        }

        $this->url = $url;
        $this->scheme = $parsedUrl['scheme'];
        $this->path = $parsedUrl['path'];
    }

    public function isFileLocation(): bool
    {
        return $this->scheme === 'file';
    }

    public function toFile(): File
    {
        if (! $this->isFileLocation()) {
            throw new RowBloomException($this->url.' is not a file');
        }

        // TODO: fromUrl
        return File::fromPath($this->path);
    }
}
