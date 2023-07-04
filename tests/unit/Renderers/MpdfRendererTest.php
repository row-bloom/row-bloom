<?php

use ElaborateCode\RowBloom\Options;
use ElaborateCode\RowBloom\Renderers\MpdfRenderer;
use ElaborateCode\RowBloom\Renderers\Renderer;
use ElaborateCode\RowBloom\Renderers\RendererFactory;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Html;

it('factorize')
    ->expect(fn () => app()->make(RendererFactory::class)->make(Renderer::Mpdf))
    ->toBeInstanceOf(MpdfRenderer::class);

it('renders and get (basic)')
    ->with([
        'example 1' => [
            'template' => Html::fromString('<h1>Title</h1><p>Bold text</p><div>Normal text</div>'),
            'css' => Css::fromString('p {font-weight: bold;}'),
            'options' => app()->make(Options::class),
        ],
    ])
    ->expect(function ($template, $css, $options) {
        return app()->make(RendererFactory::class)->make(Renderer::Mpdf)->render($template, $css, $options)->get();
    })
    // ? more assertions
    ->toBeString();
