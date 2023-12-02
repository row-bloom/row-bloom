<?php

namespace RowBloom\RowBloom\Renderers\Sizing;

class BoxSize
{
    public readonly Length $width;

    public readonly Length $height;

    final public function __construct(string|Length $width, string|Length $height)
    {
        $this->width = $width instanceof Length ? $width : Length::fromDimension($width);

        $this->height = $height instanceof Length ? $height : Length::fromDimension($height);
    }

    public function toLandscape(): static
    {
        return new static($this->height, $this->width);
    }
}
