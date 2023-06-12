<?php

namespace ElaborateCode\RowBloom\Types;

use Stringable;

class Css implements Stringable
{
    public function __construct(protected string $css)
    {
    }

    public function __toString(): string
    {
        return $this->css;
    }

    // TODO: append prepend
}
