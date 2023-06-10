<?php

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Renderers\PhpChromeRenderer;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

it('renders', function () {
    $saveTo = new File(__DIR__ . '/yo.pdf');

    $pdf = (new PhpChromeRenderer(
        new InterpolatedTemplate([
            '<h1>Title</h1><p>Bold text</p><div>Normal text</div>',
        ]),
        new Css('p {font-weight: bold;}')
    ));

    // dump($pdf->getRendering());
    $pdf->save($saveTo);
})
    // ->skip('todo')
;
