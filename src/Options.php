<?php

namespace ElaborateCode\RowBloom;

class Options
{

    /**
     * .
     *
     * @param array $margins
     * - Like Css number number,number number,number,number,number.
     * - Unit in millimeter
     */
    public function __construct(
        public ?int $perPage = null,

        public string $paperSize = 'A4',
        public string $layout = 'portrait', // landscape|portrait

        public array $margins = [16, 15, 16, 15],

        public ?string $headerLeft = null,
        public ?string $headerCenter = null,
        public ?string $headerRight = null,

        public ?string $footerLeft = null,
        public ?string $footerCenter = null,
        public ?string $footerRight = null,

        public ?int $pageNumbering = null,

        public ?string $metadataTitle = null,
        public ?string $metadataAuthor = null,
        public ?string $metadataSubject = null,
        public ?string $metadataKeywords = null,

        // font
        // security ?
        // compression ?
    ) {
    }
}
