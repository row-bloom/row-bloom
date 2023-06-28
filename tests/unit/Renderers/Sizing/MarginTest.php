<?php

use ElaborateCode\RowBloom\Renderers\Sizing\Margin;
use ElaborateCode\RowBloom\Renderers\Sizing\Length;

it('constructs', function (array|string $input, array $expected, string $unit = Length::PIXEL_UNIT) {
    $margin = new Margin($input, $unit);

    expect($margin->all())->toEqual($expected);
})->with([
    'Single value' => [
        'input' => '5',
        'expected' => [
            'marginTop' => 5,
            'marginRight' => 5,
            'marginBottom' => 5,
            'marginLeft' => 5,
        ],
    ],
    'Two value' => [
        'input' => ['5', 6.1],
        'expected' => [
            'marginTop' => 5,
            'marginRight' => 6.1,
            'marginBottom' => 5,
            'marginLeft' => 6.1,
        ],
    ],
    'For value' => [
        'input' => [1, 2, 3, 4],
        'expected' => [
            'marginTop' => 1,
            'marginRight' => 2,
            'marginBottom' => 3,
            'marginLeft' => 4,
        ],
    ],
    'Picas unit' => [
        'input' => [1],
        'expected' => [
            'marginTop' => 1,
            'marginRight' => 1,
            'marginBottom' => 1,
            'marginLeft' => 1,
        ],
        'unit' => Length::PICA_UNIT,
    ],
]);

it('constructs mixed units', function (array $input, array $expected, string $unit = Length::PIXEL_UNIT) {
    $margin = new Margin($input, $unit);

    expect($margin->all())->toEqual($expected);
})->with([
    'cm,in,pt,pc' => [
        'input' => ['1 cm', '1 in', '1 pt', '1 pc'],
        'expected' => [
            'marginTop' => 37.7953,
            'marginRight' => 96,
            'marginBottom' => 1.3333,
            'marginLeft' => 16,
        ],
    ],
]);

it('converts', function (array $input, array $expected, string $unit, string $outputUnit) {
    $margin = new Margin($input, $unit);

    expect($margin->allIn($outputUnit))->toEqual($expected);
})->with([
    'pc -> pc' => [
        'input' => [1],
        'expected' => [
            'marginTop' => 1,
            'marginRight' => 1,
            'marginBottom' => 1,
            'marginLeft' => 1,
        ],
        'unit' => Length::PICA_UNIT,
        'outputUnit' => Length::PICA_UNIT,
    ],
    'cm -> mm' => [
        'input' => [1, 2],
        'expected' => [
            'marginTop' => 10,
            'marginRight' => 20,
            'marginBottom' => 10,
            'marginLeft' => 20,
        ],
        'unit' => Length::CENTIMETER_UNIT,
        'outputUnit' => Length::MILLIMETER_UNIT,
    ],
]);
