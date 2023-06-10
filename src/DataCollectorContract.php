<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Types\Table;

interface DataCollectorContract
{
    public function getTable(string $path): Table;
}

// READER_XLSX
// READER_XLS
// READER_XML
// READER_ODS
// READER_SLK
// READER_GNUMERIC
// READER_HTML
// READER_CSV
