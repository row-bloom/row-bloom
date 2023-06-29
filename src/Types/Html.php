<?php

namespace ElaborateCode\RowBloom\Types;

use Stringable;

final class Html implements Stringable
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
}
