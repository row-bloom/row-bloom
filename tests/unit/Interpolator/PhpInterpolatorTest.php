<?php

use ElaborateCode\RowBloom\Interpolators\Interpolator;
use ElaborateCode\RowBloom\Interpolators\InterpolatorFactory;
use ElaborateCode\RowBloom\Types\Html;
use ElaborateCode\RowBloom\Types\Table;

it('default interpolator', function () {
    $data = [
        ['title' => 'lorem', 'content' => 'Lorem ipsum delorme'],
        ['title' => 'FOO', 'content' => 'Bar baz'],
    ];

    $template = '<h1><?= $title ?></h1><p><?= $content ?></p>';

    // ! expect contain
    $expected =
        '<h1>lorem</h1><p>Lorem ipsum delorme</p>'.
        '<h1>FOO</h1><p>Bar baz</p>';

    $interpolator = app()->make(InterpolatorFactory::class)->make(Interpolator::Php);

    expect($interpolator->interpolate(Html::fromString($template), Table::fromArray($data)))->toEqual($expected);
});
