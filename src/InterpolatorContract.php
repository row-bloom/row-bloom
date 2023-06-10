<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Types\Table;

interface InterpolatorContract
{
    /**
     * Interpolates the values of each row into the template
     */
    public function interpolate(string $template, Table $table): array;
}
