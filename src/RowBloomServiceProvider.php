<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\DataLoaders\DataLoaderFactory;
use RowBloom\RowBloom\DataLoaders\Folder\FolderDataLoader;
use RowBloom\RowBloom\DataLoaders\Json\JsonDataLoader;
use RowBloom\RowBloom\DataLoaders\Spreadsheets\SpreadsheetDataLoader;
use RowBloom\RowBloom\Interpolators\InterpolatorFactory;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\Renderers\RendererFactory;

class RowBloomServiceProvider
{
    public function register(): void
    {
        app()->singleton(DataLoaderFactory::class, DataLoaderFactory::class);
        app()->singleton(InterpolatorFactory::class, InterpolatorFactory::class);
        app()->singleton(RendererFactory::class, RendererFactory::class);
        app()->singleton(Support::class, Support::class);
    }

    public function boot(): void
    {
        /** @var Support */
        $support = app()->get(Support::class);

        $support->registerDataLoaderDriver(FolderDataLoader::NAME, FolderDataLoader::class)
            ->registerDataLoaderDriver(JsonDataLoader::NAME, JsonDataLoader::class)
            ->registerDataLoaderDriver(SpreadsheetDataLoader::NAME, SpreadsheetDataLoader::class);

        $support->registerInterpolatorDriver(PhpInterpolator::NAME, PhpInterpolator::class);

        $support->registerRendererDriver(HtmlRenderer::NAME, HtmlRenderer::class);
    }
}
