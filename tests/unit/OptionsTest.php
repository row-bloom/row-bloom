<?php

use RowBloom\CssSizing\BoxArea;
use RowBloom\CssSizing\PaperFormat;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\RowBloomException;

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

test('validateMargin: fails', function (PaperFormat $paperFormat, string|array $margin) {
    $options = new Options;
    $options->format = $paperFormat;
    $options->margin = BoxArea::new($margin);

    expect(fn () => $options->validateMargin())->toThrow(RowBloomException::class);
})->with([
    ['paperFormat' => PaperFormat::_A5, 'margin' => '0mm 74mm'], // A5:w = 148mm
]);

test('validateMargin: passes', function (PaperFormat $paperFormat, string|array $margin) {
    $options = new Options;
    $options->format = $paperFormat;
    $options->margin = BoxArea::new($margin);

    expect(fn () => $options->validateMargin())->not->toThrow(RowBloomException::class);
})->with([
    ['paperFormat' => PaperFormat::_A5, 'margin' => '73mm'],
]);
