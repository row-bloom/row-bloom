<?php

namespace ElaborateCode\RowBloom\Renderers\Sizing;

enum LengthUnit: string
{
    case PIXEL_UNIT = 'px';
    case CENTIMETER_UNIT = 'cm';
    case MILLIMETER_UNIT = 'mm';
    case INCH_UNIT = 'in';
    case POINT_UNIT = 'pt';
    case PICA_UNIT = 'pc';

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
}
