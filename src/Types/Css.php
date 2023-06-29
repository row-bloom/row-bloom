<?php

namespace ElaborateCode\RowBloom\Types;

use Stringable;

final class Css implements Stringable
{
    public function __construct(protected string $css)
    {
    }

    public function __toString(): string
    {
        return $this->css;
    }

    public function prepend(string|Css $css): static
    {
        $this->css = $css.$this->css;

        return $this;
    }

    public function append(string|Css $css): static
    {
        $this->css = $this->css.$css;

        return $this;
    }
}
