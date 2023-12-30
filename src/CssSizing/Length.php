<?php

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/length
 * @see https://www.w3.org/TR/css-sizing-3
 * @see https://www.w3.org/TR/css-syntax-3
 */

namespace RowBloom\CssSizing;

use Stringable;

class Length implements Stringable
{
    public static function fromDimension(string $value): static
    {
        $value = trim((string) $value);

        $parsed = static::parseLengthDimension($value);

        return Length::fromNumberUnit($parsed['value'], LengthUnit::from($parsed['unit']));
    }

    public static function fromNumberUnit(float|int $value, LengthUnit $unit): static
    {
        if (! is_numeric($value)) {
            throw new CssSizingException("Not numeric value '{$value}'");
        }

        return new static($value, $unit);
    }

    final protected function __construct(
        public readonly float|int $value,
        public readonly LengthUnit $unit,
        public ?LengthUnit $readUnit = null,
    ) {
        $this->readUnit ??= $this->unit;
    }

    // TODO: setReference
    // TODO: support functional notation. calc(50% - 2em)

    /**
     * @return array{value: float, unit: string}
     *
     * @see https://www.w3.org/TR/css-values-3/#lengths
     */
    protected static function parseLengthDimension(string $value): array
    {
        $value = trim($value);

        if ($value === '0') {
            return ['value' => 0, 'unit' => 'px'];
        }

        $units = implode('|', LengthUnit::absoluteUnits());
        $regex = "/^(?<value>\d+(\.\d+)?)(?<unit>{$units})$/";

        preg_match($regex, $value, $parsed) ?:
            throw new CssSizingException("{Invalid CSS dimension: '{$value}' (must be in <number><unit> format).");

        /** @phpstan-ignore-next-line */
        return $parsed;
    }

    public function setReadUnit(LengthUnit $readUnit): static
    {
        $this->readUnit = $readUnit;

        return $this;
    }

    public function convert(LengthUnit $readUnit): static
    {
        return (clone $this)->setReadUnit($readUnit);
    }

    public function value(LengthUnit $readUnit = null): float
    {
        if ($this->unit === $this->readUnit & is_null($readUnit)) {
            return $this->value;
        }

        if (! is_null($readUnit)) {
            return $this->value * LengthUnit::absoluteUnitsEquivalence($this->unit, $readUnit);
        }

        return $this->value * LengthUnit::absoluteUnitsEquivalence($this->unit, $this->readUnit);
    }

    public function toFloat(LengthUnit $readUnit = null): float
    {
        return $this->value($readUnit);
    }

    public function toString(LengthUnit $readUnit = null): string
    {
        return $this->value($readUnit).($readUnit?->value ?? $this->readUnit->value);
    }

    public function __toString(): string
    {
        return $this->value().$this->readUnit->value;
    }

    public function toPxFloat(): float
    {
        return $this->toFloat(LengthUnit::PIXEL);
    }

    public function toMmFloat(): float
    {
        return $this->toFloat(LengthUnit::MILLIMETER);
    }

    public function toCmFloat(): float
    {
        return $this->toFloat(LengthUnit::CENTIMETER);
    }

    public function toInFloat(): float
    {
        return $this->toFloat(LengthUnit::INCH);
    }

    public function toPtFloat(): float
    {
        return $this->toFloat(LengthUnit::POINT);
    }

    public function toPcFloat(): float
    {
        return $this->toFloat(LengthUnit::PICA);
    }

    public function toMmString(): string
    {
        return $this->toString(LengthUnit::MILLIMETER);
    }

    public function toCmString(): string
    {
        return $this->toString(LengthUnit::CENTIMETER);
    }

    public function toInString(): string
    {
        return $this->toString(LengthUnit::INCH);
    }

    public function toPtString(): string
    {
        return $this->toString(LengthUnit::POINT);
    }

    public function toPcString(): string
    {
        return $this->toString(LengthUnit::PICA);
    }
}
