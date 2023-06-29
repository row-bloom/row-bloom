# Row bloom

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elaborate-code/row-bloom.svg?style=flat-square)](https://packagist.org/packages/elaborate-code/row-bloom)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elaborate-code/row-bloom/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/elaborate-code/row-bloom/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elaborate-code/row-bloom/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/elaborate-code/row-bloom/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elaborate-code/row-bloom.svg?style=flat-square)](https://packagist.org/packages/elaborate-code/row-bloom)

![illustaton](./illustration.png)

## Installation

```bash
composer require elaborate-code/row-bloom
```

Requires:

- PHP 8.1
- sockets extension

## Usage

```php
(new RowBloom)
    ->addTable(Table::fromArray([
        ['title' => 'title1', 'body' => 'body1'],
        ['title' => 'title2', 'body' => 'body2'],
    ]))
    ->addTable(Table::fromArray([
        ['title' => 'title3', 'body' => 'body3'],
        ['title' => 'title4', 'body' => 'body4'],
    ]))
    ->setInterpolator(Interpolator::Twig)
    ->setTemplate(new Template('
        <h1>{{title}}</h1>
        <p>Bold text</p>
        <div>{{body}}</div>
    '))
    ->setOption('perPage', 2)
    ->setOption('landscape', false)
    ->setOption('format', PaperFormat::FORMAT_A4)
    ->setOption('displayHeaderFooter', true)
    ->addCss(new Css('
        p {font-weight: bold;}
    '))
    // ---------------------------
    // ->setRenderer(Renderer::Mpdf)
    // ->setOption('margin', '25.4 mm')
    // ->setOption('rawHeader', '{DATE j-m-Y}|y|z')
    // ->setOption('rawFooter', 'x|y|{PAGENO}/{nb}')
    // ---------------------------
    ->setRenderer(Renderer::PhpChrome)
    ->setOption('margin', '1 in')
    ->setOption('rawHeader', '
        <div class="header" style="font-size:10px">
            <span class="date"></span>
            <span class="title"></span>
        </div>
    ')
    ->setOption('rawFooter', '
        <div class="footer" style="font-size:10px">
            <span class="pageNumber"></span> of <span class="totalPages"></span>
        </div>
    ')
    // ---------------------------
    ->save(File::fromPath(__DIR__.'/foo.pdf'));
```

### Data

Provide data using `addTable` or `addTablePath`.

A driver will be picked automatically for each table path.

Available drivers:

- Spreadsheet (parses local `xlsx`, `xls`, `xml`, `ods`, `slk`, `gnumeric`, `html`, `csv` files).
- Json (todo)

### Template

Pick a driver using `setInterpolator`, and provide a template with `setTemplate` or `setTemplatePath`.

The available interpolators are:

- Twig (default)
- Php (todo)
- Blade (todo)

All interpolators are available in `ElaborateCode\RowBloom\Interpolators\Interpolator` enum, and you can provide a custom one as long as you implement `ElaborateCode\RowBloom\InterpolatorContract`

### Rendering

Pick a driver using `setRenderer`, and optionally provide css with `addCss` or `addCssPath`.

The available renderers are:

- Html (default)
- Php chrome
- Mpdf

All renderers are available in `ElaborateCode\RowBloom\Renderers\Renderer` enum, and you can provide a custom one as long as you implement `ElaborateCode\RowBloom\RendererContract`

### Options

Each renderer has its own way to handle **margins**, **header**, **footer**, **paper sizing** and more. This package tries to act as a wrapper and give the same output from same options regardless of rendering library.

> The hard part XD

The main options are the one offered by the browser print UI.

![browser print options](./browser_print_options.png)

## Testing

```bash
./vendor/bin/pest
./vendor/bin/phpstan
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
