<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Renderers\Sizing\PaperFormat;

class Options
{
    /**
     * .
     *
     * @param  (float|int|string)[]|string  $margins
     * - Like Css number number,number number,number,number,number.
     * - Unit in millimeter
     * @param  ?PaperFormat  $format
     * - Takes precedence over `$width` and `$height`
     * - Affected by `$landscape`
     */
    public function __construct(
        public bool $displayHeaderFooter = false,
        // * special classes: date, url, title, pageNumber, totalPages
        public ?string $rawHeader = null,
        public ?string $rawFooter = null,

        public bool $printBackground = false,
        public bool $preferCSSPageSize = false,

        public ?int $perPage = null,

        public bool $landscape = false,
        public ?PaperFormat $format = null, // takes priority over width or height
        public ?string $width = null,
        public ?string $height = null,

        public array|string $margins = [57, 57, 57, 57], // TODO: singular

        public ?string $metadataTitle = null,
        public ?string $metadataAuthor = null,
        public ?string $metadataCreator = null,
        public ?string $metadataSubject = null,
        public ?string $metadataKeywords = null,

        // scale ?
        // font ?
        // security ?
        // compression ?
    ) {
    }

    // TODO: enum unit or object scale
    public function resolvePaperSize(string $unit)
    {
        if (isset($this->format)) {
            $size = $this->format->size($unit);

            return $this->landscape ? [$size[1], $size[0]] : $size;
        }

        if (isset($this->width) && isset($this->height)) {
            // todo handle units
            // !default is mm?
            return [$this->width, $this->height];
        }

        $size = PaperFormat::FORMAT_A4->size($unit);

        return $this->landscape ? [$size[1], $size[0]] : $size;
    }
}
