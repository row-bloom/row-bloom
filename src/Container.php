<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\DataCollectors\DataCollectorFactory;
use ElaborateCode\RowBloom\Interpolators\InterpolatorFactory;
use ElaborateCode\RowBloom\Renderers\RendererFactory;

app()->singleton(DataCollectorFactory::class, DataCollectorFactory::class);
app()->singleton(InterpolatorFactory::class, InterpolatorFactory::class);
app()->singleton(RendererFactory::class, RendererFactory::class);
