# Row bloom

[![Latest Version on Packagist](https://img.shields.io/packagist/v/row-bloom/spreadsheet-data-loader.svg?style=flat-square)](https://packagist.org/packages/row-bloom/spreadsheet-data-loader)
[![Pest action](https://img.shields.io/github/actions/workflow/status/row-bloom/row-bloom/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/row-bloom/row-bloom/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Pint action](https://img.shields.io/github/actions/workflow/status/row-bloom/row-bloom/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/row-bloom/row-bloom/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/row-bloom/spreadsheet-data-loader.svg?style=flat-square)](https://packagist.org/packages/row-bloom/spreadsheet-data-loader)

> [!IMPORTANT]
> This is a sub-split, for development, pull requests and issues, visit: <https://github.com/row-bloom/row-bloom>

## Installation

```bash
composer require row-bloom/spreadsheet-data-loader
```

```php
use RowBloom\RowBloom\Support;
use RowBloom\SpreadsheetDataLoader\SpreadsheetDataLoader;

app()->make(Support::class)
    ->registerDataLoaderDriver(SpreadsheetDataLoader::NAME, SpreadsheetDataLoader::class);
```

Requires:

- PHP >= 8.1

`phpoffice/phpspreadsheet` dependencies:

- ext-ctype
- ext-dom
- ext-fileinfo
- ext-gd
- ext-iconv
- ext-libxml
- ext-mbstring
- ext-simplexml
- ext-xml
- ext-xmlreader
- ext-xmlwriter
- ext-zip
- ext-zlib

## Usage

```php
use RowBloom\SpreadsheetDataLoader\SpreadsheetDataLoader;
use RowBloom\RowBloom\Interpolators\PhpInterpolator;
use RowBloom\RowBloom\Renderers\HtmlRenderer;
use RowBloom\RowBloom\RowBloom;

app()->get(RowBloom::class)
    ->addTablePath('foo.csv')
    ->addTablePath('bar.xlsx')
    ->setInterpolator(PhpInterpolator::NAME)
    ->setTemplate('
        <h1>{{ title }}</h1>
        <p>Bold text</p>
        <div>{{ body }}</div>
    ')
    ->setRenderer(HtmlRenderer::class)
    ->save(__DIR__.'/foo.pdf');
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
