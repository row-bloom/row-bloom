<?php

use ElaborateCode\RowBloom\Support;

it('getSupportedTableFileExtensions', function() {
    /** @var Support */
    $support = app()->get(Support::class);

    expect($support->getSupportedTableFileExtensions())
        ->toHaveKeys(['json', 'csv']);
});
