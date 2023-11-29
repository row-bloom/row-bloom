<?php

use RowBloom\RowBloom\Renderers\Sizing\LengthUnit;
use RowBloom\RowBloom\Renderers\Sizing\Margin;

it('constructs', function (array|string $input, array $expected, LengthUnit $unit = null) {
    expect((new Margin($input, $unit))->allRaw())->toEqual($expected);
})->with([
    'Single value' => [
        'input' => '5',
        'expected' => ['top' => 5, 'right' => 5, 'bottom' => 5, 'left' => 5],
        'unit' => LengthUnit::PIXEL,
    ],
    'Two value' => [
        'input' => ['5', 6.1],
        'expected' => ['top' => 5, 'right' => 6.1, 'bottom' => 5, 'left' => 6.1],
        'unit' => LengthUnit::PIXEL,
    ],
    'For value' => [
        'input' => [1, 2, 3, 4],
        'expected' => [
            'top' => 1,
            'right' => 2,
            'bottom' => 3,
            'left' => 4,
        ],
        'unit' => LengthUnit::PIXEL,
    ],
    'Picas unit' => [
        'input' => [1],
        'expected' => ['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],
        'unit' => LengthUnit::PICA,
    ],
]);

it('constructs mixed units', function (array $input, array $expected, LengthUnit $unit = null) {
    expect((new Margin($input, $unit))->allRaw())->toEqual($expected);
})->with([
    'cm,in,pt,pc' => [
        'input' => ['1cm', '1in', '1pt', '1pc'],
        'expected' => ['top' => 37.7953, 'right' => 96, 'bottom' => 1.3333, 'left' => 16],
        'unit' => LengthUnit::PIXEL,
    ],
]);

it('converts', function (array $input, array $expected, LengthUnit $unit, LengthUnit $outputUnit) {
    expect((new Margin($input, $unit))->allRawIn($outputUnit))->toEqual($expected);
})->with([
    'pc -> pc' => [
        'input' => [1],
        'expected' => ['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],
        'unit' => LengthUnit::PICA,
        'outputUnit' => LengthUnit::PICA,
    ],
    'cm -> mm' => [
        'input' => [1, 2],
        'expected' => ['top' => 10, 'right' => 20, 'bottom' => 10, 'left' => 20],
        'unit' => LengthUnit::CENTIMETER,
        'outputUnit' => LengthUnit::MILLIMETER,
    ],
]);
