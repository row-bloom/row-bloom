<?php

use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;
use RowBloom\TwigInterpolator\TwigInterpolator;

it('interpolates values into templates', function (array $data, string $template, string $match) {
    expect((new TwigInterpolator)->interpolate(Html::fromString($template), Table::fromArray($data)))
        ->toEqual($match);
})->with([
    'Example 1' => [
        'data' => [
            ['title' => 'lorem', 'content' => 'Lorem ipsum delorme'],
            ['title' => 'FOO', 'content' => 'Bar baz'],
        ],
        'template' => '<h1>{{ title }}</h1><p>{{ content }}</p>',
        'match' => '<h1>lorem</h1><p>Lorem ipsum delorme</p>'.
            '<h1>FOO</h1><p>Bar baz</p>',
    ],
]);

test('Page breaks', function (int $rowsCount, ?int $perPage, int $breakCount) {
    $rendering = (new TwigInterpolator)
        ->interpolate(Html::fromString(''), Table::fromArray(array_fill(0, $rowsCount, [])), $perPage);

    expect(substr_count($rendering, 'page-break'))->toBe($breakCount);
})->with([
    [
        'rowsCount' => 2,
        'perPage' => null,
        'breaksCount' => 0,
    ],
    [
        'rowsCount' => 2,
        'perPage' => 1,
        'breaksCount' => 1,
    ],
    [
        'rowsCount' => 5,
        'perPage' => 3,
        'breaksCount' => 1,
    ],
    [
        'rowsCount' => 10,
        'perPage' => 2,
        'breaksCount' => 4,
    ],
    [
        'rowsCount' => 60,
        'perPage' => 60,
        'breaksCount' => 0,
    ],
]);
