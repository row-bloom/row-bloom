<?php

namespace ElaborateCode\RowBloom\Renderers\Sizing;

use Exception;

final class Length
{
    const PIXEL_UNIT = 'px';

    const CENTIMETER_UNIT = 'cm';

    const MILLIMETER_UNIT = 'mm';

    const INCH_UNIT = 'in';

    const POINT_UNIT = 'pt';

    const PICA_UNIT = 'pc';

    /**
     * @var (int|float)[][] Absolute units only
     *
     * ! multiple conversions cause small value infidelity
     * Example: 1 in -> 25.4mm -> 1.00076 in
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

    /**
     * Relative:
     * - To font:
     *   - em: Relative to the font-size of the parent element.
     *   - rem: Relative to the font-size of the root element (usually the <html> element).
     *   - ex: Relative to the x-height of the current font. The x-height is typically the height of lowercase letters.
     *   - ch: Relative to the width of the "0" (zero) character of the current font.
     * - To screen:
     *   - vw: Relative to 1% of the viewport's width.
     *   - vh: Relative to 1% of the viewport's height.
     *   - vmin: Relative to 1% of the viewport's smaller dimension (width or height).
     *   - vmax: Relative to 1% of the viewport's larger dimension (width or height).
     * - To page size:
     *   - percent: Represents a percentage relative to the parent element.
     */

    private float $value;

    // public static function fromString(){}
    // public static function fromNumber(){}

    public function __construct(float|int|string $value, private string $unit)
    {
        // TODO: if relative unit require a reference

        if (! isset(self::RATIOS_TABLE[$this->unit])) {
            throw new Exception("Invalid unit {$this->unit}");
        }

        // TODO: handle string?

        $this->value = (float) $value;
    }

    public function convert(string $to): self
    {
        $to = strtolower(trim($to));

        if ($this->unit === $to) {
            return clone $this;
        }

        if (! isset(self::RATIOS_TABLE[$this->unit][$to])) {
            throw new Exception("Invalid conversion from {$this->unit} to {$to}");
        }

        return new self($this->value() * self::RATIOS_TABLE[$this->unit][$to], $to);
    }

    public function value()
    {
        return $this->value;
    }
}
