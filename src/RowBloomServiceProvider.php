<?php

namespace RowBloom\RowBloom;

use Illuminate\Container\Container;
use RowBloom\RowBloom\DataLoaders\DataLoaderFactory;
use RowBloom\RowBloom\DataLoaders\FolderDataLoader;
use RowBloom\RowBloom\DataLoaders\JsonDataLoader;
use RowBloom\RowBloom\Interpolators\InterpolatorFactory;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\Renderers\RendererFactory;

class RowBloomServiceProvider
{
    public function register(): void
    {
        Container::getInstance()->singleton(DataLoaderFactory::class, DataLoaderFactory::class);
        Container::getInstance()->singleton(InterpolatorFactory::class, InterpolatorFactory::class);
        Container::getInstance()->singleton(RendererFactory::class, RendererFactory::class);
        Container::getInstance()->singleton(Support::class, Support::class);
    }

    public function boot(): void
    {
        /** @var Support */
        $support = Container::getInstance()->get(Support::class);

        $support->registerDataLoaderDriver(FolderDataLoader::NAME, FolderDataLoader::class)
            ->registerDataLoaderDriver(JsonDataLoader::NAME, JsonDataLoader::class);

        $support->registerInterpolatorDriver(PhpInterpolator::NAME, PhpInterpolator::class);

        $support->registerRendererDriver(HtmlRenderer::NAME, HtmlRenderer::class);
    }
}
