<?php

use RowBloom\RowBloom\Options;

test('setFromArray()', function () {
    $defaultOptions = new Options;

    $options = (new Options)
        ->setFromArray([
            'preferCssPageSize' => ! $defaultOptions->preferCssPageSize,
            'print_background' => ! $defaultOptions->printBackground,
        ]);

    expect($options->preferCssPageSize)->toBe(! $defaultOptions->preferCssPageSize);
    expect($options->printBackground)->toBe(! $defaultOptions->printBackground);
});
