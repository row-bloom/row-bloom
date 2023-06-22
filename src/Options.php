<?php

namespace ElaborateCode\RowBloom;

/**
 * .
 *
 * @var array $margin
 * - Like Css number number,number number,number,number,number.
 * - Unit in millimeter
 */
class Options
{
    public function __construct(
        public ?int $perPage = null,

        public string $paperSize = 'A4',
        public string $layout = 'P',

        public array $margins = [1],

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

        // security ?
        // compression ?
    ) {
    }
}
