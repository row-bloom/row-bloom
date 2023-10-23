<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\DataCollectors\DataCollectorFactory;
use RowBloom\RowBloom\Interpolators\InterpolatorFactory;
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
}
