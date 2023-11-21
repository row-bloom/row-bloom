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
        $filePath = realpath($url);

        if ($filePath !== false) {
            $url = (substr(PHP_OS, 0, 3) === 'WIN' ? 'file:///' : 'file://').$filePath;
        }

        $parsedUrl = parse_url($url);

        if (
            ! is_array($parsedUrl) ||
            ! isset($parsedUrl['scheme']) ||
            (! isset($parsedUrl['path']) && ! isset($parsedUrl['host']))
        ) {
            throw new RowBloomException($url.' is not a valid URL neither an absolute path for an existing file');
        }

        $this->url = $url;
        $this->scheme = $parsedUrl['scheme'];
        $this->path = $parsedUrl['path'] ?? '/';
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
