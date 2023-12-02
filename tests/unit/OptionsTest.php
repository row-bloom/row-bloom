<?php

use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Renderers\Sizing\BoxArea;
use RowBloom\RowBloom\Renderers\Sizing\PaperFormat;
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
    ['paperFormat' => PaperFormat::FORMAT_A5, 'margin' => '74mm'],
]);

test('validateMargin: passes', function (PaperFormat $paperFormat, string|array $margin) {
    $options = new Options;
    $options->format = $paperFormat;
    $options->margin = BoxArea::new($margin);

    expect(fn () => $options->validateMargin())->not->toThrow(RowBloomException::class);
})->with([
    ['paperFormat' => PaperFormat::FORMAT_A5, 'margin' => '73mm'],
]);
