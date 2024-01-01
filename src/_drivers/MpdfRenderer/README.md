# Row bloom

[![Latest Version on Packagist](https://img.shields.io/packagist/v/row-bloom/mpdf-renderer.svg?style=flat-square)](https://packagist.org/packages/row-bloom/mpdf-renderer)
[![Pest action](https://img.shields.io/github/actions/workflow/status/row-bloom/row-bloom/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/row-bloom/row-bloom/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Pint action](https://img.shields.io/github/actions/workflow/status/row-bloom/row-bloom/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/row-bloom/row-bloom/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/row-bloom/mpdf-renderer.svg?style=flat-square)](https://packagist.org/packages/row-bloom/mpdf-renderer)

> [!IMPORTANT]
> This is a sub-split, for development, pull requests and issues, visit: <https://github.com/row-bloom/row-bloom>

## Installation

```bash
composer require row-bloom/mpdf-renderer
```

```php
use RowBloom\RowBloom\Support;
use RowBloom\MpdfRenderer\MpdfRenderer;

app()->get(Support::class);
    ->registerInterpolatorDriver(MpdfRenderer::NAME, MpdfRenderer::class)
```

Requires:

- PHP >= 8.1
- ext-gd
- ext-mbstring
- ext-zlib

## Usage

```php
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\MpdfRenderer\MpdfRenderer;
use RowBloom\RowBloom\RowBloom;
use RowBloom\RowBloom\Types\Table;

app()->get(RowBloom::class)
    ->addTable(Table::fromArray([
        ['title' => 'Title3', 'body' => 'body3'],
        ['title' => 'Title4', 'body' => 'body4'],
    ]))
    ->setInterpolator(PhpInterpolator::class)
    ->setTemplate('
        <h1><?= $title ?></h1>
        <p>Bold text</p>
        <div><?= $body ?></div>
    ')
    ->setRenderer(MpdfRenderer::class)
    ->save(__DIR__.'/foo.pdf');
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
