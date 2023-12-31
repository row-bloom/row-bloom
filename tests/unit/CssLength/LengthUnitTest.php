<?php

use RowBloom\CssLength\LengthUnit;

test('absoluteUnitsEquivalence', function (string $from, string $to, float $equivalence) {
    expect(LengthUnit::absoluteUnitsEquivalence($from, $to))->toEqual($equivalence);
})->with([
    ['from' => 'mm', 'to' => 'cm', 'equivalence' => 0.1],
    ['from' => 'cm', 'to' => 'mm', 'equivalence' => 10],
    ['from' => 'in', 'to' => 'cm', 'equivalence' => 2.54],
    ['from' => 'cm', 'to' => 'in', 'equivalence' => 1 / 2.54],
    ['from' => 'mm', 'to' => 'in', 'equivalence' => 1 / 25.4],
]);

test('absoluteUnits')
    ->expect(LengthUnit::absoluteUnits())
    ->toContain('px', 'pt', 'pc', 'cm', 'mm', 'in');
