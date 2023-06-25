<?php

namespace ElaborateCode\RowBloom;

class Options
{
    /**
     * .
     *
     * @param  int[]|string[]  $margins
     * - Like Css number number,number number,number,number,number.
     * - Unit in millimeter
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
        public string $format = 'A4', // takes priority over width or height
        // check Mpdf\PageFormat::getSizeFromName
        public ?string $width = null,
        public ?string $height = null,

        public array $margins = [16, 15, 16, 15],

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

    // TODO: margins and size must have units; default to px
}
