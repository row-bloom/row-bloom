<?php

use ElaborateCode\RowBloom\Renderers\PhpChromeRenderer;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

it('renders', function () {
    $html = (new PhpChromeRenderer(
        new InterpolatedTemplate([
            '<h1>Title</h1><p>Bold text</p><div>Normal text</div>',
        ]),
        new Css('p {font-weight: bold;}')
    ))
        ->getRendering();

    dump($html);
})->skip('todo');
