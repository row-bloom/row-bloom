<?php

use RowBloom\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Interpolators\TwigInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\Renderers\MpdfRenderer;
use RowBloom\RowBloom\Renderers\PhpChromeRenderer;
use RowBloom\RowBloom\Support;

it('lists capabilities', function () {
    /** @var Support */
    $support = app()->get(Support::class);

    expect($support->getSupportedTableFileExtensions())
        ->toHaveKeys(['json', 'csv', 'xlsx']);

    expect($support->getDataCollectorDrivers())
        ->toHaveKeys(['Spreadsheet', 'Folder', 'Json'])
        ->toContain(SpreadsheetDataCollector::class);

    expect($support->getInterpolatorDrivers())
        ->toHaveKeys(['Php', 'Twig'])
        ->toContain(TwigInterpolator::class, PhpInterpolator::class);

    expect($support->getRendererDrivers())
        ->toHaveKeys(['HTML'])
        ->toContain(PhpChromeRenderer::class, MpdfRenderer::class);

    expect($support->getRendererOptionsSupport(HtmlRenderer::NAME)['metadataKeywords'])
        ->toBeFalse();

    expect($support->getRendererOptionsSupport(MpdfRenderer::NAME)['metadataKeywords'])
        ->toBeTrue();

    expect($support->getRendererOptionsSupport('yo'))
        ->toBeNull();
});
