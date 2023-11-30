<?php

use RowBloom\RowBloom\Renderers\Sizing\LengthUnit;
use RowBloom\RowBloom\Renderers\Sizing\Margin;

it('constructs', function (array|string $input, LengthUnit $unit, array $expected) {
    expect((new Margin($input, $unit))->allRaw())->toEqual($expected);
})->with([
    'Single value' => [
        'input' => '5',
        'unit' => LengthUnit::PIXEL,
        'expected' => ['top' => 5, 'right' => 5, 'bottom' => 5, 'left' => 5],
    ],
    'Two value' => [
        'input' => ['5', 6.1],
        'unit' => LengthUnit::PIXEL,
        'expected' => ['top' => 5, 'right' => 6.1, 'bottom' => 5, 'left' => 6.1],
    ],
    'For value' => [
        'input' => [1, 2, 3, 4],
        'unit' => LengthUnit::PIXEL,
        'expected' => [
            'top' => 1,
            'right' => 2,
            'bottom' => 3,
            'left' => 4,
        ],
    ],
    'Picas unit' => [
        'input' => [1],
        'unit' => LengthUnit::PICA,
        'expected' => ['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],
    ],
]);

it('constructs mixed units', function (array $input, LengthUnit $unit, array $expected) {
    expect((new Margin($input, $unit))->allRaw())->toEqual($expected);
})->with([
    'cm,in,pt,pc' => [
        'input' => ['1cm', '1in', '1pt', '1pc'],
        'unit' => LengthUnit::PIXEL,
        'expected' => ['top' => 37.7953, 'right' => 96, 'bottom' => 1.3333, 'left' => 16],
    ],
]);

it('converts', function (array $input, LengthUnit $unit, array $expected, LengthUnit $outputUnit) {
    expect((new Margin($input, $unit))->allRawIn($outputUnit))->toEqual($expected);
})->with([
    'pc -> pc' => [
        'input' => [1],
        'unit' => LengthUnit::PICA,
        'expected' => ['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],
        'outputUnit' => LengthUnit::PICA,
    ],
    'cm -> mm' => [
        'input' => [1, 2],
        'unit' => LengthUnit::CENTIMETER,
        'expected' => ['top' => 10, 'right' => 20, 'bottom' => 10, 'left' => 20],
        'outputUnit' => LengthUnit::MILLIMETER,
    ],
]);
