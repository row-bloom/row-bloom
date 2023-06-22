<?php

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Options;
use ElaborateCode\RowBloom\Renderers\HtmlRenderer;
use ElaborateCode\RowBloom\Renderers\RendererFactory;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

it('renders and saves', function () {
    $saveTo = new File(__DIR__.'/../../temp/foo.html');

    $renderer = RendererFactory::make('html');

    expect($renderer)->toBeInstanceOf(HtmlRenderer::class);

    // TODO: more assertions
    expect($renderer->getRendering(
        new InterpolatedTemplate([
            '<h1>Title</h1><p>Bold text</p><div>Normal text</div>',
        ]),
        new Css('p {font-weight: bold;}'),
        new Options
    ))->toBeString();

    expect($renderer->save($saveTo))->toBeTrue();
});
