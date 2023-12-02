<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\Renderers\Sizing\BoxArea;
use RowBloom\RowBloom\Renderers\Sizing\Length;
use RowBloom\RowBloom\Renderers\Sizing\LengthUnit;
use RowBloom\RowBloom\Renderers\Sizing\PaperFormat;
use RowBloom\RowBloom\Utils\CaseConverter;

class Options
{
    public ?Length $width = null;

    public ?Length $height = null;

    public BoxArea $margin;

    /**
     * .
     *
     * @param  string[]|string  $margin
     * - Format like CSS (all_sides)|(top_bottom right_left)|(top right_left bottom)|(top right bottom left).
     * - Only string types support adding unit, numerical types fallback to *default unit*.
     * @param  ?PaperFormat  $format
     * - Takes precedence over `$width` and `$height`
     * - Affected by `$landscape`
     * - If no size indicator is given, A4 paper format will be used.
     */
    public function __construct(
        public bool $displayHeaderFooter = true,
        public ?string $headerTemplate = null,
        public ?string $footerTemplate = null,

        public bool $printBackground = false,
        public bool $preferCssPageSize = false,

        public ?int $perPage = null,

        public bool $landscape = false,
        public ?PaperFormat $format = null,
        string|length $width = null,
        string|length $height = null,

        array|string $margin = '1in',

        // scale ?
        // security ?
        // compression ?
    ) {
        $this->margin = BoxArea::new($margin);

        if (! is_null($width)) {
            $this->width = $width instanceof Length ? $width : Length::fromString($width);
        }

        if (! is_null($height)) {
            $this->height = $height instanceof Length ? $height : Length::fromString($height);
        }
    }

    /** @param  array<string, mixed>  $options */
    public function setFromArray(array $options): static
    {
        foreach ($options as $key => $value) {
            $key = CaseConverter::snakeToCamel($key);

            if (! property_exists($this, $key)) {
                continue;
            }

            $this->$key = $value;
        }

        return $this;
    }

    /** @return array{0: float, 1: float} [width, height] */
    public function resolvePaperSize(LengthUnit $unit): array
    {
        if (isset($this->format)) {
            $size = $this->format->size($unit);

            return $this->landscape ? [$size[1], $size[0]] : $size;
        }

        if (isset($this->width) && isset($this->height)) {
            return [$this->width->toFloat($unit), $this->height->toFloat($unit)];
        }

        $size = PaperFormat::FORMAT_A4->size($unit);

        return $this->landscape ? [$size[1], $size[0]] : $size;
    }

    /** @throws RowBloomException */
    public function validateMargin(): void
    {
        $pageSize = $this->resolvePaperSize(LengthUnit::PIXEL);

        $width = $this->margin->right->toFloat(LengthUnit::PIXEL) +
            $this->margin->left->toFloat(LengthUnit::PIXEL);

        $height = $this->margin->top->toFloat(LengthUnit::PIXEL) +
            $this->margin->bottom->toFloat(LengthUnit::PIXEL);

        if ($height >= $pageSize[1]) {
            throw new RowBloomException('Margin top and bottom must not overlap');
        }

        if ($width >= $pageSize[0]) {
            throw new RowBloomException('Margin right and left must not overlap');
        }
    }
}
