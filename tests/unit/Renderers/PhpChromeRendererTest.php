<?php

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Renderers\PhpChromeRenderer;
use ElaborateCode\RowBloom\Renderers\RendererFactory;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

it('renders', function () {
    $saveTo = new File(__DIR__.'/../../temp/foo.pdf');

    $renderer = RendererFactory::make('chromium-pdf');

    expect($renderer)->toBeInstanceOf(PhpChromeRenderer::class);

    // TODO: more assertions
    expect($renderer->getRendering(
        new InterpolatedTemplate([
            '<h1>Title</h1><p>Bold text</p><div>Normal text</div>',
        ]),
        new Css('p {font-weight: bold;}')
    ))->toBeString();

    expect($renderer->save($saveTo))->toBeTrue();
});
