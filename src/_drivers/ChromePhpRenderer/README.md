# Row bloom

[![Latest Version on Packagist](https://img.shields.io/packagist/v/row-bloom/chrome-php-renderer.svg?style=flat-square)](https://packagist.org/packages/row-bloom/chrome-php-renderer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/row-bloom/chrome-php-renderer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/row-bloom/chrome-php-renderer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/row-bloom/chrome-php-renderer/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/row-bloom/chrome-php-renderer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/row-bloom/chrome-php-renderer.svg?style=flat-square)](https://packagist.org/packages/row-bloom/chrome-php-renderer)

## Installation

```bash
composer require row-bloom/chrome-php-renderer
```

```php
use RowBloom\RowBloom\Support;
use RowBloom\ChromePhpRenderer\ChromePhpRenderer;

app()->get(Support::class);
    ->registerInterpolatorDriver(ChromePhpRenderer::NAME, ChromePhpRenderer::class)
```

Requires:

- PHP >= 8.1

`chrome-php/chrome` dependencies:

- ext-sockets

## Usage

```php
use RowBloom\ChromePhpRenderer\ChromePhpRenderer;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\RowBloom;

app()->get(RowBloom::class)
    ->addTable([
        ['title' => 'Title1', 'body' => 'body1'],
        ['title' => 'Title2', 'body' => 'body2'],
    ])
    ->setInterpolator(PhpInterpolator::class)
    ->setTemplate('
        <h1><?= $title ?></h1>
        <p>Bold text</p>
        <div><?= $body ?></div>
    ')
    ->setRenderer(ChromePhpRenderer::class)
    ->save(__DIR__.'/foo.pdf');
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
