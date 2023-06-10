<?php

use ElaborateCode\RowBloom\Interpolators\TwigInterpolator;
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

    expect((new TwigInterpolator)->interpolate(new Template($template), new Table($data)))
        ->toEqual($expected);
});