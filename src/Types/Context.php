<?php

namespace RowBloom\RowBloom\Types;

use RowBloom\RowBloom\RowBloom;
use RowBloom\RowBloom\Support;

class Context
{
    public function __construct(
        public readonly RowBloom $rowBloom,
        public readonly Support $support
    ) {
    }
}
