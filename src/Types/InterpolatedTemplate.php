<?php

namespace ElaborateCode\RowBloom\Types;

class InterpolatedTemplate
{
    /**
     * @param  array<array<mixed>>  $interpolatedTemplate
     */
    public function __construct(protected array $interpolatedTemplate)
    {
    }

    public function toArray(): array
    {
        return $this->interpolatedTemplate;
    }
}
