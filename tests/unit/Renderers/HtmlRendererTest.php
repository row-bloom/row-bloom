<?php

use ElaborateCode\RowBloom\Options;
use ElaborateCode\RowBloom\Renderers\HtmlRenderer;
use ElaborateCode\RowBloom\Renderers\Renderer;
use ElaborateCode\RowBloom\Renderers\RendererFactory;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Html;

it('renders and saves', function () {
    $renderer = RendererFactory::getInstance()->make(Renderer::Html);

    expect($renderer)->toBeInstanceOf(HtmlRenderer::class);

    // ? more assertions
    expect(
        $renderer->render(
            Html::fromString('<h1>Title</h1><p>Bold text</p><div>Normal text</div>'),
            new Css('p {font-weight: bold;}'),
            new Options
        )->get()
    )->toBeString();
});
