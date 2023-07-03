<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Renderers\RendererFactory;
use Illuminate\Container\Container as IlluminateContainer;

$container = new IlluminateContainer;
$container->instance(Container::class, $container);

define('ROW_BLOOM_CONTAINER', $container);

$container->singleton(DataCollectorFactory::class, DataCollectorFactory::class);
$container->singleton(InterpolatorFactory::class, InterpolatorFactory::class);
$container->singleton(RendererFactory::class, RendererFactory::class);
