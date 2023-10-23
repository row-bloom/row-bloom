<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;

interface InterpolatorContract
{
    public function interpolate(Html $template, Table $table, int $perPage = null): Html;
}
