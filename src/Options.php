<?php

namespace ElaborateCode\RowBloom;

class Options
{
    public function __construct(
        public ?int $perPage = null,
        public string $pageSize = 'A4',
        public string $pageOrientation = 'P',
        public int $margins = 1, // in millimeter
        public ?string $header = null,
        public ?string $footer = null,
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
