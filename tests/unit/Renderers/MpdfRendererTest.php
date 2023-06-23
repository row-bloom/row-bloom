<?php

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Options;
use ElaborateCode\RowBloom\Renderers\MpdfRenderer;
use ElaborateCode\RowBloom\Renderers\RendererFactory;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

it('renders', function () {
    // $saveTo = new File(__DIR__.'/../../temp/foo.pdf');

    $renderer = RendererFactory::make('*mpdf');

    expect($renderer)->toBeInstanceOf(MpdfRenderer::class);

    expect($renderer->getRendering(
        new InterpolatedTemplate([
            '<h1>Title</h1><p>Bold text</p><div>Normal text</div>',
        ]),
        new Css('p {font-weight: bold;}'),
        new Options
    ))->toBeString();
});
