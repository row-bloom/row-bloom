<?php

namespace ElaborateCode\RowBloom\Renderers\Sizing;

use Exception;

final class Length
{
    /**
     * @var (int|float)[][] Absolute units only
     *
     * ! multiple conversions cause small value infidelity
     * Example: 1 in -> 25.4mm -> 1.00076 in
     *
     * TODO: enum keys?
     */
    private const RATIOS_TABLE = [
        'px' => [
            'cm' => 0.02646,
            'mm' => 0.2646,
            'in' => 0.0104,
            'pt' => 0.75,
            'pc' => 0.0625,
        ],
        'cm' => [
            'px' => 37.7953,
            'mm' => 10,
            'in' => 0.3937,
            'pt' => 28.3465,
            'pc' => 2.3622,
        ],
        'mm' => [
            'px' => 3.7795,
            'cm' => 0.1,
            'in' => 0.0394,
            'pt' => 2.8346,
            'pc' => 0.2362,
        ],
        'in' => [
            'px' => 96,
            'cm' => 2.54,
            'mm' => 25.4,
            'pt' => 72,
            'pc' => 6,
        ],
        'pt' => [
            'px' => 1.3333,
            'cm' => 0.03528,
            'mm' => 0.3528,
            'in' => 0.0138,
            'pc' => 0.0833,
        ],
        'pc' => [
            'px' => 16,
            'cm' => 0.4233,
            'mm' => 4.2333,
            'in' => 0.1667,
            'pt' => 12,
        ],
    ];

    private float $value;

    public static function fromNumber(float|int|string $value, LengthUnit $unit): self
    {
        return new self($value, $unit);
    }

    public function __construct(float|int|string $value, private LengthUnit $unit)
    {
        // TODO: if relative unit require a reference

        // TODO: handle string?

        $this->value = (float) $value;
    }

    public function convert(LengthUnit $to): self
    {
        if ($this->unit === $to) {
            return clone $this;
        }

        if (! isset(self::RATIOS_TABLE[$this->unit->value][$to->value])) {
            throw new Exception("Invalid conversion from {$this->unit->value} to {$to->value}");
        }

        return new self($this->value() * self::RATIOS_TABLE[$this->unit->value][$to->value], $to);
    }

    public function value()
    {
        return $this->value;
    }
}
