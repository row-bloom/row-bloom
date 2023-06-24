<?php

namespace ElaborateCode\RowBloom;

class Options
{
    /**
     * .
     *
     * @param  array  $margins
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
        public ?string $width = null,
        public ?string $height = null,

        public array $margins = [16, 15, 16, 15],

        public ?string $metadataTitle = null,
        public ?string $metadataAuthor = null,
        public ?string $metadataSubject = null,
        public ?string $metadataKeywords = null,

        // scale ?
        // font ?
        // security ?
        // compression ?
    ) {
    }
}

// https://www.geeksforgeeks.org/how-to-print-header-and-footer-on-every-printed-page-of-a-document-in-css/

// https://developer.mozilla.org/en-US/docs/Web/CSS/@page
// https://www.w3.org/TR/1998/REC-CSS2-19980512/page.html
// https://www.quackit.com/css/at-rules/css_bottom-center_at-rule.cfm
// https://webplatform.github.io/docs/css/atrules/page/
// https://drafts.csswg.org/css-page-3/#at-ruledef-bottom-center

// https://github.com/chrome-php/chrome#print-as-pdf
// https://github.com/spiritix/php-chrome-html2pdf

// https://mpdf.github.io/paging/using-page.html

// @rule
// selection can target
// - all pages
// - odd or even pages
// - custom

// $pageOrientation is
