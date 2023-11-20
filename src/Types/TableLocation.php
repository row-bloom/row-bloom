<?php

/**
 * @see https://en.wikipedia.org/wiki/URL
 * @see https://en.wikipedia.org/wiki/File_URI_scheme
 * @see https://datatracker.ietf.org/doc/html/rfc3986 Uniform Resource Identifier (URI): Generic Syntax
 */

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
        // TODO: validate string format instead of being real
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

        // ? default path to /
        if (! is_array($parsedUrl) || ! isset($parsedUrl['scheme']) || ! isset($parsedUrl['path'])) {
            throw new RowBloomException($url.' has no valid URL scheme and is not a valid absolute file path');
        }

        $this->url = $url;
        $this->scheme = $parsedUrl['scheme'];
        $this->path = $parsedUrl['path'];
    }

    public function isScheme(string $scheme): bool
    {
        return $this->scheme === $scheme;
    }

    public function isFileLocation(): bool
    {
        return $this->isScheme('file');
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
