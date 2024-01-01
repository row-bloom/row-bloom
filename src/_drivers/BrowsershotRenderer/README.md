# Row bloom

[![Latest Version on Packagist](https://img.shields.io/packagist/v/row-bloom/browsershot-renderer.svg?style=flat-square)](https://packagist.org/packages/row-bloom/browsershot-renderer)
[![Pest action](https://img.shields.io/github/actions/workflow/status/row-bloom/row-bloom/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/row-bloom/row-bloom/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Pint action](https://img.shields.io/github/actions/workflow/status/row-bloom/row-bloom/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/row-bloom/row-bloom/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/row-bloom/browsershot-renderer.svg?style=flat-square)](https://packagist.org/packages/row-bloom/browsershot-renderer)

> [!IMPORTANT]
> This is a sub-split, for development, pull requests and issues, visit: <https://github.com/row-bloom/row-bloom>

## Installation

```bash
composer require row-bloom/browsershot-renderer
```

```php
use RowBloom\RowBloom\Support;
use RowBloom\BrowsershotRenderer\BrowsershotRenderer;

app()->get(Support::class);
    ->registerInterpolatorDriver(BrowsershotRenderer::NAME, BrowsershotRenderer::class)
```

Requires:

Depends on Chrome binary and NodeJs Puppeteer library.

- PHP >= 8.1

`spatie/browsershot` dependencies:

- ext-exif
- ext-fileinfo
- ext-json
- ext-mbstring

## Usage

```php
use RowBloom\BrowsershotRenderer\BrowsershotRenderer;
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
    ->setRenderer(BrowsershotRenderer::class)
    ->save(__DIR__.'/foo.pdf');
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
