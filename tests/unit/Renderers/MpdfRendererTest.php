<?php

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Renderers\MpdfRenderer;
use ElaborateCode\RowBloom\Renderers\RendererFactory;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

it('renders', function () {
    // $saveTo = new File(__DIR__.'./../../temp/foo.pdf');

    $renderer = RendererFactory::make(
        'html-to-pdf',
        new InterpolatedTemplate([
            '<h1>Title</h1><p>Bold text</p><div>Normal text</div>',
        ]),
        new Css('p {font-weight: bold;}')
    );

    expect($renderer)->toBeInstanceOf(MpdfRenderer::class);
});