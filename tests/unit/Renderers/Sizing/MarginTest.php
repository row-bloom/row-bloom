<?php

use RowBloom\RowBloom\Renderers\Sizing\LengthUnit;
use RowBloom\RowBloom\Renderers\Sizing\Margin;

it('constructs', function (array|string $input, array $expected, LengthUnit $unit = null) {
    expect((new Margin($input, $unit))->allRaw())->toEqual($expected);
})->with([
    'Single value' => [
        'input' => '5',
        'expected' => [
            'marginTop' => 5,
            'marginRight' => 5,
            'marginBottom' => 5,
            'marginLeft' => 5,
        ],
        'unit' => LengthUnit::PIXEL_UNIT,
    ],
    'Two value' => [
        'input' => ['5', 6.1],
        'expected' => [
            'marginTop' => 5,
            'marginRight' => 6.1,
            'marginBottom' => 5,
            'marginLeft' => 6.1,
        ],
        'unit' => LengthUnit::PIXEL_UNIT,
    ],
    'For value' => [
        'input' => [1, 2, 3, 4],
        'expected' => [
            'marginTop' => 1,
            'marginRight' => 2,
            'marginBottom' => 3,
            'marginLeft' => 4,
        ],
        'unit' => LengthUnit::PIXEL_UNIT,
    ],
    'Picas unit' => [
        'input' => [1],
        'expected' => [
            'marginTop' => 1,
            'marginRight' => 1,
            'marginBottom' => 1,
            'marginLeft' => 1,
        ],
        'unit' => LengthUnit::PICA_UNIT,
    ],
]);

it('constructs mixed units', function (array $input, array $expected, LengthUnit $unit = null) {
    expect((new Margin($input, $unit))->allRaw())->toEqual($expected);
})->with([
    'cm,in,pt,pc' => [
        'input' => ['1 cm', '1 in', '1 pt', '1 pc'],
        'expected' => [
            'marginTop' => 37.7953,
            'marginRight' => 96,
            'marginBottom' => 1.3333,
            'marginLeft' => 16,
        ],
        'unit' => LengthUnit::PIXEL_UNIT,
    ],
]);

it('converts', function (array $input, array $expected, LengthUnit $unit, LengthUnit $outputUnit) {
    expect((new Margin($input, $unit))->allRawIn($outputUnit))->toEqual($expected);
})->with([
    'pc -> pc' => [
        'input' => [1],
        'expected' => [
            'marginTop' => 1,
            'marginRight' => 1,
            'marginBottom' => 1,
            'marginLeft' => 1,
        ],
        'unit' => LengthUnit::PICA_UNIT,
        'outputUnit' => LengthUnit::PICA_UNIT,
    ],
    'cm -> mm' => [
        'input' => [1, 2],
        'expected' => [
            'marginTop' => 10,
            'marginRight' => 20,
            'marginBottom' => 10,
            'marginLeft' => 20,
        ],
        'unit' => LengthUnit::CENTIMETER_UNIT,
        'outputUnit' => LengthUnit::MILLIMETER_UNIT,
    ],
]);
