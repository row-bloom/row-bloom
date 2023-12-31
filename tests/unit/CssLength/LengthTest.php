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
    $length = Length::fromDimension('10px')->convert(LengthUnit::INCH);

    expect($length->value)->toEqual(10);
    expect($length->unit)->toEqual(LengthUnit::PIXEL);

    expect($length->toFloat())->toEqual(10 * (1 / 96));
    expect($length->toString())->toEqual((10 * (1 / 96)).'in');
});

it('throws an exception when calling an undefined method')
    ->expect(fn () => Length::fromDimension('10px')->undefinedMethod())
    ->throws(BadMethodCallException::class);
