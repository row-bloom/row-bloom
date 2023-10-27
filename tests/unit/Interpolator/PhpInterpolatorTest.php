<?php

use RowBloom\RowBloom\Interpolators\InterpolatorFactory;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;

test('php interpolator', function (array|Table $data, string|Html $template, string $match) {
    expect(
        app()->make(InterpolatorFactory::class)
            ->make(PhpInterpolator::NAME)
            ->interpolate(Html::fromString($template), Table::fromArray($data))
    )
        ->toEqual($match);
})->with([
    'example 1' => [
        'data' => [
            ['title' => 'lorem', 'content' => 'Lorem ipsum delorme'],
            ['title' => 'FOO', 'content' => 'Bar baz'],
        ],
        'template' => '<h1><?= $title ?></h1><p><?= $content ?></p>',
        'match' => '<h1>lorem</h1><p>Lorem ipsum delorme</p>'.
            '<h1>FOO</h1><p>Bar baz</p>',
    ],
]);

test('Page breaks', function (array $data, ?int $perPage, int $breakCount) {
    $rendering = (new PhpInterpolator)
        ->interpolate(Html::fromString(''), Table::fromArray($data), $perPage);

    expect(substr_count($rendering, 'page-break'))->toBe($breakCount);
})->with([
    'No breaks' => [
        'data' => array_fill(0, 2, []),
        'perPage' => null,
        'breaksCount' => 0,
    ],
    '1 - 1' => [
        'data' => array_fill(0, 2, []),
        'perPage' => 1,
        'breaksCount' => 1,
    ],
    '3 - 2' => [
        'data' => array_fill(0, 5, []),
        'perPage' => 3,
        'breaksCount' => 1,
    ],
    '60' => [
        'data' => array_fill(0, 60, []),
        'perPage' => 60,
        'breaksCount' => 0,
    ],
]);
