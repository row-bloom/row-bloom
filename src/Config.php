<?php

namespace RowBloom\RowBloom;

class Config
{
    public function __construct(
        public ?string $nodeBinaryPath = null,
        public ?string $npmBinaryPath = null,
        public ?string $nodeModulesPath = null,
        public ?string $chromePath = null,
    ) {
    }
}
