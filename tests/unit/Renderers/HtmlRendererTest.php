<?php

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Renderers\HtmlRenderer;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

it('renders and saves', function () {
    $saveTo = new File(__DIR__.'./../../temp/foo.html');

    $html = (new HtmlRenderer(
        new InterpolatedTemplate([
            '<h1>Title</h1><p>Bold text</p><div>Normal text</div>',
        ]),
        new Css('p {font-weight: bold;}')
    ));

    expect($html->getRendering())->toBeString();

    expect($html->save($saveTo))->toBeTrue();
});
