<?php

namespace RowBloom\BrowsershotRenderer;

class BrowsershotConfig
{
    public function __construct(
        public ?string $nodeBinaryPath = null,
        public ?string $npmBinaryPath = null,
        public ?string $nodeModulesPath = null,
        public ?string $chromePath = null,
    ) {
    }
}
