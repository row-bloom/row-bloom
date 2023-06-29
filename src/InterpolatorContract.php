<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Types\Html;
use ElaborateCode\RowBloom\Types\Table;

interface InterpolatorContract
{
    public function interpolate(Html $template, Table $table, ?int $perPage = null): Html;
}
