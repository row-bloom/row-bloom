<?php

namespace RowBloom\RowBloom;

use RowBloom\CssLength\BoxArea;
use RowBloom\CssLength\BoxSize;
use RowBloom\CssLength\Length;
use RowBloom\CssLength\PaperFormat;
use RowBloom\CssLength\PaperSizeResolver;

class Options
{
    public ?Length $width = null;

    public ?Length $height = null;

    public BoxArea $margin;

    public ?PaperFormat $format = null;

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
        null|string|PaperFormat $format = null,
        string|length|null $width = null,
        string|length|null $height = null,

        array|string $margin = '1in',

        // scale ?
        // security ?
        // compression ?
    ) {
        $this->setFormat($format)
            ->setMargin($margin)
            ->setWidth($width)
            ->setHeight($height);
    }

    public function setFormat(null|string|PaperFormat $format): static
    {
        if ($format instanceof PaperFormat || is_null($format)) {
            $this->format = $format;

            return $this;
        }

        $this->format = PaperFormat::from($format);

        return $this;
    }

    public function setMargin(array|string|BoxArea $margin): static
    {
        if ($margin instanceof BoxArea) {
            $this->margin = $margin;

            return $this;
        }

        $this->margin = BoxArea::new($margin);

        return $this;
    }

    public function setWidth(null|string|Length $width): static
    {
        if ($width instanceof Length || is_null($width)) {
            $this->width = $width;

            return $this;
        }

        $this->width = Length::fromDimension($width);

        return $this;
    }

    public function setHeight(null|string|Length $height): static
    {
        if ($height instanceof Length || is_null($height)) {
            $this->height = $height;

            return $this;
        }

        $this->height = Length::fromDimension($height);

        return $this;
    }

    /** @param  array<string, mixed>  $options */
    public function setFromArray(array $options): static
    {
        foreach ($options as $key => $value) {
            match ($key) {
                'displayHeaderFooter', 'display_header_footer' => $this->displayHeaderFooter = $value,
                'headerTemplate', 'header_template' => $this->headerTemplate = $value,
                'footerTemplate', 'footer_template' => $this->footerTemplate = $value,
                'printBackground', 'print_background' => $this->printBackground = $value,
                'preferCssPageSize', 'prefer_css_page_size' => $this->preferCssPageSize = $value,
                'perPage', 'per_page' => $this->perPage = $value,
                'format' => $this->setFormat($value),
                'landscape' => $this->landscape = $value,
                'width' => $this->setWidth($value),
                'height' => $this->setHeight($value),
                'margin' => $this->setMargin($value),
                default => null,
            };
        }

        return $this;
    }

    public function resolvePaperSize(): BoxSize
    {
        return PaperSizeResolver::init(
            width: $this->width,
            height: $this->height,
            paperFormat: $this->format,
            landscape: $this->landscape,
        )->resolve();
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
