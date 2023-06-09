<?php

use ElaborateCode\RowBloom\DataCollectors\DcFactory;
use ElaborateCode\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDc;

it('makes', function () {
    expect((new DcFactory)->make())->toBeInstanceOf(SpreadsheetDc::class);
});
