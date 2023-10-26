<?php

use RowBloom\RowBloom\Interpolators\InterpolatorFactory;
use RowBloom\RowBloom\Interpolators\TwigInterpolator;
use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;

test('twig', function (array|Table $data, string|Html $template, string $match) {
    expect(
        app()->make(InterpolatorFactory::class)
            ->make(TwigInterpolator::NAME)
            ->interpolate(Html::fromString($template), Table::fromArray($data))
    )
        // ! expect contain
        ->toEqual($match);
})->with([
    'example 1' => [
        'data' => [
            ['title' => 'lorem', 'content' => 'Lorem ipsum delorme'],
            ['title' => 'FOO', 'content' => 'Bar baz'],
        ],
        'template' => '<h1>{{ title }}</h1><p>{{ content }}</p>',
        'match' => '<h1>lorem</h1><p>Lorem ipsum delorme</p>'.
            '<h1>FOO</h1><p>Bar baz</p>',
    ],
]);
