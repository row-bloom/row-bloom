<?php

namespace RowBloom\RowBloom;

class InvalidTypeException extends RowBloomException
{
    public function __construct(string $driverName, string $driverType)
    {
        // ($foo) must be of type yo, string given
        parent::__construct("'{$driverName}' is not a valid {$driverType}");
    }
}
