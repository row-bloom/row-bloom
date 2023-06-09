<?php

use ElaborateCode\RowBloom\Renderers\HtmlRenderer;

it('renders', function () {
    $html = (new HtmlRenderer)
        ->render(
            [
                '<h1>Title</h1><p>Bold text</p><div>Normal text</div>',
            ],
            'p {font-weight: bold;}'
        );

    // dump($html);
    expect($html)->toBeString();
});
