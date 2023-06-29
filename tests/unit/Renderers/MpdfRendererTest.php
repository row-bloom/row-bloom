<?php

use ElaborateCode\RowBloom\Options;
use ElaborateCode\RowBloom\Renderers\MpdfRenderer;
use ElaborateCode\RowBloom\Renderers\Renderer;
use ElaborateCode\RowBloom\Renderers\RendererFactory;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

it('renders', function () {
    $renderer = RendererFactory::getInstance()->make(Renderer::Mpdf);

    expect($renderer)->toBeInstanceOf(MpdfRenderer::class);

    expect(
        $renderer->render(
            new InterpolatedTemplate([
                '<h1>Title</h1><p>Bold text</p><div>Normal text</div>',
            ]),
            new Css('p {font-weight: bold;}'),
            new Options
        )->get()
    )->toBeString();
});
