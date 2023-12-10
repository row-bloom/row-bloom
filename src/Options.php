<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\Renderers\Sizing\BoxArea;
use RowBloom\RowBloom\Renderers\Sizing\BoxSize;
use RowBloom\RowBloom\Renderers\Sizing\Length;
use RowBloom\RowBloom\Renderers\Sizing\PageSizeResolver;
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
            $this->width = $width instanceof Length ? $width : Length::fromDimension($width);
        }

        if (! is_null($height)) {
            $this->height = $height instanceof Length ? $height : Length::fromDimension($height);
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

    public function resolvePaperSize(): BoxSize
    {
        return PageSizeResolver::resolve(
            width: $this->width,
            height: $this->height,
            paperFormat: $this->format,
            landscape: $this->landscape,
        );
    }

    /** @throws RowBloomException */
    public function validateMargin(): void
    {
        $pageSize = $this->resolvePaperSize();

        $width = $this->margin->right->toPxFloat() +
            $this->margin->left->toPxFloat();

        $height = $this->margin->top->toPxFloat() +
            $this->margin->bottom->toPxFloat();

        if ((int) $height >= (int) $pageSize->height->toPxFloat()) {
            throw new RowBloomException('Margin top and bottom must not overlap');
        }

        if ((int) $width >= (int) $pageSize->width->toPxFloat()) {
            throw new RowBloomException('Margin right and left must not overlap');
        }
    }
}
