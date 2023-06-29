<?php

use ElaborateCode\RowBloom\Options;
use ElaborateCode\RowBloom\Renderers\MpdfRenderer;
use ElaborateCode\RowBloom\Renderers\Renderer;
use ElaborateCode\RowBloom\Renderers\RendererFactory;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Html;

it('renders', function ($template, $css, $options) {
    $renderer = RendererFactory::getInstance()->make(Renderer::Mpdf);

    expect($renderer)->toBeInstanceOf(MpdfRenderer::class);

    expect($renderer->render($template, $css, $options)->get())->toBeString();
})->with([
    "example 1" => [
        'template' => Html::fromString('<h1>Title</h1><p>Bold text</p><div>Normal text</div>'),
        'css' => Css::fromString('p {font-weight: bold;}'),
        'options' => new Options
    ]
]);
