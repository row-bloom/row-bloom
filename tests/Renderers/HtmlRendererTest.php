<?php

use ElaborateCode\RowBloom\Renderers\HtmlRenderer;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

it('renders', function () {
    $html = (new HtmlRenderer(
        new InterpolatedTemplate([
            '<h1>Title</h1><p>Bold text</p><div>Normal text</div>',
        ]),
        'p {font-weight: bold;}'
    ))
        ->render();

    // dump($html);
    expect($html->getRendering())->toBeString();
});
