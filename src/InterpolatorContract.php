<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Types\Table;
use ElaborateCode\RowBloom\Types\Template;

interface InterpolatorContract
{
    /**
     * Interpolates the values of each row into the template
     */
    public function interpolate(Template $template, Table $table): array;
}
