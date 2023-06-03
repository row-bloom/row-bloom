<?php

namespace ElaborateCode\RowBloom\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ElaborateCode\RowBloom\RowBloom
 */
class RowBloom extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ElaborateCode\RowBloom\RowBloom::class;
    }
}
