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
    public function __construct(protected Container $container)
    {
    }

    public function register(): void
    {
        $this->container->singleton(Support::class);
        $this->container->singleton(DataLoaderFactory::class);
        $this->container->singleton(InterpolatorFactory::class);
        $this->container->singleton(RendererFactory::class);
    }

    public function boot(): void
    {
        /** @var Support */
        $support = $this->container->get(Support::class);

        $support->registerDataLoaderDriver(FolderDataLoader::NAME, FolderDataLoader::class)
            ->registerDataLoaderDriver(JsonDataLoader::NAME, JsonDataLoader::class);

        $support->registerInterpolatorDriver(PhpInterpolator::NAME, PhpInterpolator::class);

        $support->registerRendererDriver(HtmlRenderer::NAME, HtmlRenderer::class);
    }
}
