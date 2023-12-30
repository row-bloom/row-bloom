<?php

namespace RowBloom\RowBloom\Interpolators;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;

interface Contract
{
    public function interpolate(Html $template, Table $table, int $perPage = null, Config $config = null): Html;
}
