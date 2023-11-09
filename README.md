# Row bloom

[![Latest Version on Packagist](https://img.shields.io/packagist/v/row-bloom/row-bloom.svg?style=flat-square)](https://packagist.org/packages/row-bloom/row-bloom)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/row-bloom/row-bloom/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/row-bloom/row-bloom/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/row-bloom/row-bloom/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/row-bloom/row-bloom/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/row-bloom/row-bloom.svg?style=flat-square)](https://packagist.org/packages/row-bloom/row-bloom)

This package is used to generate PDFs using a table of data with one or many rows, and a template that gets applied for each row.

The goal is to allow the usage of any templating engine with any PDF generation library, by abstracting them as drivers and trying to ensure an idempotent output no matter what driver the user picks.

![illustration](./illustration.png)

```php
use RowBloom\RowBloom\DataLoaders\FolderDataLoader;
use RowBloom\RowBloom\DataLoaders\JsonDataLoader;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\Sizing\PaperFormat;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\Support;

$support = (new Support)
    ->registerDataLoaderDriver(FolderDataLoader::NAME, FolderDataLoader::class)
    ->registerDataLoaderDriver(JsonDataLoader::NAME, JsonDataLoader::class)
    ->registerInterpolatorDriver(PhpInterpolator::NAME, PhpInterpolator::class)
    ->registerRendererDriver(HtmlRenderer::NAME, HtmlRenderer::class);

$r = rowBloom(support: $support)->rowBloom;

$r->setInterpolator('PHP')->setRenderer('Chrome')
    ->addTable([
        ['title' => 'Title1', 'body' => 'body1'],
        ['title' => 'Title2', 'body' => 'body2'],
    ])
    ->setTemplate('
        <h1><?= $title ?></h1>
        <div><?= $body ?></div>
    ')
    ->addCss('h1 {color: red;}')
    ->tapOptions(function (Options $options) {
        $options->format = PaperFormat::FORMAT_A4;
        $options->displayHeaderFooter = true;
        $options->margin = '1 in';
    })
    ->save(__DIR__.'/foo.pdf');
```

## Setup

```bash
composer require row-bloom/row-bloom
```

## Usage

Head over to the [full documentation](https://github.com/row-bloom/row-bloom/wiki).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

![class diagram](./class_diagram.drawio.png)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
