<?php

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/CSS/length
 * @see https://www.w3.org/TR/css-length-3
 * @see https://www.w3.org/TR/css-syntax-3
 */

namespace RowBloom\CssLength;

use BadMethodCallException;
use Stringable;

/**
 * @method float toPxFloat()
 * @method float toMmFloat()
 * @method float toCmFloat()
 * @method float toInFloat()
 * @method float toPtFloat()
 * @method float toPcFloat()
 * @method string toMmString()
 * @method string toCmString()
 * @method string toInString()
 * @method string toPtString()
 * @method string toPcString()
 */
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
        return new static($value, $unit);
    }

    final protected function __construct(
        public readonly float|int $value,
        public readonly LengthUnit $unit,
        public ?LengthUnit $readUnit = null,
    ) {
        $this->readUnit ??= $this->unit;
    }

    // TODO: setReference for relative units
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
            throw new CssLengthException("{Invalid CSS dimension: '{$value}' (must be in <number><unit> format).");

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

    public function value(?LengthUnit $readUnit = null): float
    {
        if ($this->unit === $this->readUnit & is_null($readUnit)) {
            return $this->value;
        }

        return LengthUnit::absoluteUnitsEquivalence(
            $this->unit,
            $readUnit ?? $this->readUnit,
            $this->value
        );
    }

    public function toFloat(?LengthUnit $readUnit = null): float
    {
        return $this->value($readUnit);
    }

    public function toString(?LengthUnit $readUnit = null): string
    {
        return $this->value($readUnit).($readUnit?->value ?? $this->readUnit->value);
    }

    public function __toString(): string
    {
        return $this->value().$this->readUnit->value;
    }

    /** @phpstan-ignore-next-line */
    public function __call($name, $arguments): float|string
    {
        if (preg_match('/^to(?<unit>..)Float$/', $name, $matchedUnit) === 1) {
            $readUnit = LengthUnit::tryFrom(strtolower($matchedUnit['unit']));

            if (! is_null($readUnit)) {
                return $this->toFloat($readUnit);
            }
        }

        if (preg_match('/^to(?<unit>..)String$/', $name, $matchedUnit) === 1) {
            $readUnit = LengthUnit::tryFrom(strtolower($matchedUnit['unit']));

            if (! is_null($readUnit)) {
                return $this->toString($readUnit);
            }
        }

        throw new BadMethodCallException('Call to undefined method '.static::class.'::'.$name.'().');
    }
}
