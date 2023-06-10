<?php

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Renderers\PhpChromeRenderer;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

it('renders', function () {
    $saveTo = new File(__DIR__.'./../../temp/foo.pdf');

    $pdf = (new PhpChromeRenderer(
        new InterpolatedTemplate([
            '<h1>Title</h1><p>Bold text</p><div>Normal text</div>',
        ]),
        new Css('p {font-weight: bold;}')
    ));

    expect($pdf->getRendering())->toBeString();

    expect($pdf->save($saveTo))->toBeTrue();
});
