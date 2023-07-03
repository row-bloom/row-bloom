<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Renderers\RendererFactory;
use Illuminate\Container\Container as IlluminateContainer;

$container = new IlluminateContainer;
$container->instance(Container::class, $container);

define('ROW_BLOOM_CONTAINER', $container);
