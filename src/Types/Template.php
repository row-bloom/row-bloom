<?php

namespace ElaborateCode\RowBloom\Types;

use Stringable;

class Template implements Stringable
{
    public function __construct(protected string $template)
    {
    }

    public function __toString(): string
    {
        return $this->template;
    }
}
