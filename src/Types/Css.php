<?php

namespace RowBloom\RowBloom\Types;

use Stringable;

final class Css implements Stringable
{
    public static function fromString(string $content): static
    {
        return new self($content);
    }

    private function __construct(protected string $content)
    {
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function prepend(string|Css $css): static
    {
        $this->content = $css.$this->content;

        return $this;
    }

    public function append(string|Css $css): static
    {
        $this->content = $this->content.$css;

        return $this;
    }
}
