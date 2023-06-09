<?php

namespace ElaborateCode\RowBloom;

// DataCollectorContract
interface DcContract
{
    public function getData(string $path): array;
}

// READER_XLSX
// READER_XLS
// READER_XML
// READER_ODS
// READER_SLK
// READER_GNUMERIC
// READER_HTML
// READER_CSV
