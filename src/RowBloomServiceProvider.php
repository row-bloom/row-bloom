<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\DataCollectors\DataCollectorFactory;
use RowBloom\RowBloom\DataCollectors\Folder\FolderDataCollector;
use RowBloom\RowBloom\DataCollectors\Json\JsonDataCollector;
use RowBloom\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;
use RowBloom\RowBloom\Interpolators\InterpolatorFactory;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Interpolators\TwigInterpolator;
use RowBloom\RowBloom\Renderers\BrowsershotRenderer;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\Renderers\MpdfRenderer;
use RowBloom\RowBloom\Renderers\PhpChromeRenderer;
use RowBloom\RowBloom\Renderers\RendererFactory;

class RowBloomServiceProvider
{
    public function register(): void
    {
        app()->singleton(DataCollectorFactory::class, DataCollectorFactory::class);
        app()->singleton(InterpolatorFactory::class, InterpolatorFactory::class);
        app()->singleton(RendererFactory::class, RendererFactory::class);
        app()->singleton(Support::class, Support::class);
    }

    public function boot(): void
    {
        /** @var Support */
        $support = app()->get(Support::class);

        $support->registerDataCollectorDriver(FolderDataCollector::NAME, FolderDataCollector::class);
        $support->registerDataCollectorDriver(JsonDataCollector::NAME, JsonDataCollector::class);
        $support->registerDataCollectorDriver(SpreadsheetDataCollector::NAME, SpreadsheetDataCollector::class);

        $support->registerInterpolatorDriver(PhpInterpolator::NAME, PhpInterpolator::class);
        $support->registerInterpolatorDriver(TwigInterpolator::NAME, TwigInterpolator::class);

        $support->registerRendererDriver(HtmlRenderer::NAME, HtmlRenderer::class);
        $support->registerRendererDriver(MpdfRenderer::NAME, MpdfRenderer::class);
        $support->registerRendererDriver(BrowsershotRenderer::NAME, BrowsershotRenderer::class);
        $support->registerRendererDriver(PhpChromeRenderer::NAME, PhpChromeRenderer::class);
    }
}
