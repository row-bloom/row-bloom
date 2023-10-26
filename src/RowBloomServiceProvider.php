<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\DataCollectors\DataCollectorFactory;
use RowBloom\RowBloom\DataCollectors\Folder\FolderDataCollector;
use RowBloom\RowBloom\DataCollectors\Json\JsonDataCollector;
use RowBloom\RowBloom\DataCollectors\Spreadsheets\SpreadsheetDataCollector;
use RowBloom\RowBloom\Interpolators\InterpolatorFactory;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Interpolators\TwigInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
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

        $support->registerDataCollectorDriver(FolderDataCollector::NAME, FolderDataCollector::class)
            ->registerDataCollectorDriver(JsonDataCollector::NAME, JsonDataCollector::class)
            ->registerDataCollectorDriver(SpreadsheetDataCollector::NAME, SpreadsheetDataCollector::class);

        $support->registerInterpolatorDriver(PhpInterpolator::NAME, PhpInterpolator::class)
            ->registerInterpolatorDriver(TwigInterpolator::NAME, TwigInterpolator::class);

        $support->registerRendererDriver(HtmlRenderer::NAME, HtmlRenderer::class);
    }
}
