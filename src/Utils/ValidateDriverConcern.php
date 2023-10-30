<?php

namespace RowBloom\RowBloom\Utils;

use RowBloom\RowBloom\RowBloomException;

trait ValidateDriverConcern
{
    protected function validateContract(string $className, string $interfaceName): void
    {
        if (! is_a($className, $interfaceName, true)) {
            throw new RowBloomException("'{$className}' does not implement '{$interfaceName}'");
        }
    }
}
