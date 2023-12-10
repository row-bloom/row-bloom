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
