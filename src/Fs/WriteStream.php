<?php

namespace ElaborateCode\RowBloom\Fs;

use InvalidArgumentException;

class WriteStream
{
    public function __construct(protected $file)
    {
        if (false === is_resource($file)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Argument must be a valid resource type. %s given.',
                    gettype($file)
                )
            );
        }
    }

    public function streamFilterAppend(string $filterName): static
    {
        stream_filter_append($this->file, $filterName);
        return $this;
    }

    public function save(string $content): bool
    {
        if (fwrite($this->file, $content) === false) {
            // ?
        };

        return fclose($this->file);
    }
}
