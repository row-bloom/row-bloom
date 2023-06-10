<?php

namespace ElaborateCode\RowBloom\Types;

class InterpolatedTemplate
{
    public function __construct(protected array $interpolatedTemplate)
    {
    }

    public function toArray(): array
    {
        return $this->interpolatedTemplate;
    }
}
