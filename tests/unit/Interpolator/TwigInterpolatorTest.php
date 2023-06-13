<?php

use ElaborateCode\RowBloom\Interpolators\InterpolatorFactory;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;
use ElaborateCode\RowBloom\Types\Table;
use ElaborateCode\RowBloom\Types\Template;

it('interpolates', function () {
    $data = [
        ['title' => 'lorem', 'content' => 'Lorem ipsum delorme'],
        ['title' => 'FOO', 'content' => 'Bar baz'],
    ];

    $template = '<h1>{{ title }}</h1><p>{{ content }}</p>';

    $expected = new InterpolatedTemplate([
        '<h1>lorem</h1><p>Lorem ipsum delorme</p>',
        '<h1>FOO</h1><p>Bar baz</p>',
    ]);

    expect(
        InterpolatorFactory::make('')
            ->interpolate(new Template($template), Table::fromArray($data))
    )->toEqual($expected);
});
