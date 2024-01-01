<?php

use RowBloom\CssLength\BoxArea;
use RowBloom\CssLength\CssBoxException;

it('string -> toStringsMap', function (array|string $input, array $expected) {
    expect(BoxArea::new($input)->toStringsMap())->toEqual($expected);
})->with([
    'String of one components' => [
        'input' => '1px',
        'expected' => ['top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px'],
    ],
    'String of two components' => [
        'input' => '5px 6.1mm',
        'expected' => ['top' => '5px', 'right' => '6.1mm', 'bottom' => '5px', 'left' => '6.1mm'],
    ],
    'String of three components' => [
        'input' => '1px 2pc 3mm',
        'expected' => ['top' => '1px', 'right' => '2pc', 'bottom' => '3mm', 'left' => '2pc'],
    ],
    'String of for components' => [
        'input' => '1px 2pc 3mm 4cm',
        'expected' => ['top' => '1px', 'right' => '2pc', 'bottom' => '3mm', 'left' => '4cm'],
    ],
]);

it('list -> toStringsList', function (array|string $input, array $expected) {
    expect(BoxArea::new($input)->toStringsList())->toEqual($expected);
})->with([
    'List of one components' => [
        'input' => ['1px'],
        'expected' => ['1px', '1px', '1px', '1px'],
    ],
    'List of two components' => [
        'input' => ['5px', '6.1mm'],
        'expected' => ['5px', '6.1mm', '5px', '6.1mm'],
    ],
    'List of three components' => [
        'input' => ['1px', '2pc', '3mm'],
        'expected' => ['1px', '2pc', '3mm', '2pc'],
    ],
    'List of for components' => [
        'input' => ['1px', '2pc', '3mm', '4cm'],
        'expected' => ['1px', '2pc', '3mm', '4cm'],
    ],
]);

it('Invalid values', function (array|string $input) {
    expect(fn () => BoxArea::new($input)->toStringsList())->toThrow(CssBoxException::class);
})->with([
    [['x' => '1px']],
    ['1px 2pc 3mm 4cm 5cm'],
]);
