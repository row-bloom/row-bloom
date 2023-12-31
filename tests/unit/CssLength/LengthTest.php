<?php

use RowBloom\CssLength\Length;
use RowBloom\CssLength\LengthUnit;

it('fromDimension', function () {
    $length = Length::fromDimension('10px');

    expect($length->value)->toEqual(10);
    expect($length->unit)->toEqual(LengthUnit::PIXEL);
});

it('fromNumberUnit', function () {
    $length = Length::fromNumberUnit(10, LengthUnit::PIXEL);

    expect($length->value)->toEqual(10);
    expect($length->unit)->toEqual(LengthUnit::PIXEL);
});

it('convert > toString > toFloat', function () {
    $length = Length::fromDimension('10cm')->convert(LengthUnit::MILLIMETER);

    expect($length->value)->toEqual(10);
    expect($length->unit)->toEqual(LengthUnit::CENTIMETER);

    expect($length->toFloat())->toEqual(100);
    expect($length->toString())->toEqual('100mm');
});

it('throws an exception when calling an undefined method')
    ->expect(fn () => Length::fromDimension('10px')->undefinedMethod())
    ->throws(BadMethodCallException::class);

it('toXyString', function (string $value, string $px, string $pt, string $pc, string $mm, string $cm, string $in) {
    $length = Length::fromDimension($value);

    expect($length->toPxString())->toBe($px);
    expect($length->toPtString())->toBe($pt);
    expect($length->toPcString())->toBe($pc);
    expect($length->toMmString())->toBe($mm);
    expect($length->toCmString())->toBe($cm);
    expect($length->toInString())->toBe($in);
})->with([
    '1in' => ['1in', '96px', '72pt', '6pc', '25.4mm', '2.54cm', '1in'],
    '2cm' => ['2cm', '75.590551181102px', '56.692913385827pt', '4.7244094488189pc', '20mm', '2cm', '0.78740157480315in'],
    '3mm' => ['3mm', '11.338582677165px', '8.503937007874pt', '0.70866141732283pc', '3mm', '0.3cm', '0.11811023622047in'],
    '5px' => ['5px', '5px', '3.75pt', '0.3125pc', '1.3229166666667mm', '0.13229166666667cm', '0.052083333333333in'],
    '10pt' => ['10pt', '13.333333333333px', '10pt', '1.6666666666667pc', '3.5277777777778mm', '0.35277777777778cm', '0.13888888888889in'],
    '2pc' => ['2pc', '32px', '24pt', '2pc', '8.4666666666667mm', '0.84666666666667cm', '0.33333333333333in'],
]);
