<?php

namespace ElaborateCode\RowBloom;

interface InterpolatorContract
{
    /**
     * Interpolates the values of each row into the template
     */
    public function interpolate(string $template, array $data): array;
}
