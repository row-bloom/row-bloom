<?php

/**
 * @see https://www.w3.org/TR/css-page-3/#page-size
 */

namespace RowBloom\CssLength;

class BoxSize
{
    public readonly Length $width;

    public readonly Length $height;

    public static function new(string|Length $width, string|Length $height): static
    {
        return new static($width, $height);
    }

    public static function fromArray(array $size): static
    {
        if (! is_string($size['width']) && ! is_string($size[0])) {
            throw new CssLengthException('Box size as an array must have a width value as string at key "width" or index 0.');
        }

        if (! is_string($size['height']) && ! is_string($size[1])) {
            throw new CssLengthException('Box size as an array must have a height value as string at key "height" or index 1.');
        }

        return new static($size['width'] ?? $size[0], $size['height'] ?? $size[1]);
    }

    final public function __construct(string|Length $width, string|Length $height)
    {
        $this->width = $width instanceof Length ? $width : Length::fromDimension($width);

        $this->height = $height instanceof Length ? $height : Length::fromDimension($height);
    }

    public function toLandscape(): static
    {
        $pxHeight = $this->height->toPxFloat();
        $pxWidth = $this->width->toPxFloat();

        if ($pxWidth > $pxHeight) {
            return new static($this->width, $this->height);
        }

        return new static($this->height, $this->width);
    }

    public function toPortrait(): static
    {
        $pxHeight = $this->height->toPxFloat();
        $pxWidth = $this->width->toPxFloat();

        if ($pxHeight > $pxWidth) {
            return new static($this->width, $this->height);
        }

        return new static($this->height, $this->width);
    }
}
