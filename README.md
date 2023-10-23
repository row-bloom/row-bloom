# Row bloom

[![Latest Version on Packagist](https://img.shields.io/packagist/v/row-bloom/row-bloom.svg?style=flat-square)](https://packagist.org/packages/row-bloom/row-bloom)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/row-bloom/row-bloom/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/row-bloom/row-bloom/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/row-bloom/row-bloom/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/row-bloom/row-bloom/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/row-bloom/row-bloom.svg?style=flat-square)](https://packagist.org/packages/row-bloom/row-bloom)

This package is used to generate PDFs using a table of data with one or many rows, and a template that gets applied for each row.

The goal is to allow the usage of any templating engine with any PDF generation library, by abstracting them as drivers and trying to ensure an idempotent output no matter what driver the user picks.

![illustration](./illustration.png)

## Installation

```bash
composer require row-bloom/row-bloom
```

Call `RowBloomServiceProvider::register()` at the entry point of your application.

```php
use RowBloom\RowBloom\RowBloomServiceProvider;

app()->make(RowBloomServiceProvider::class)->register();
```

Requires:

- PHP 8.1
- sockets extension

## Usage

```php
use RowBloom\RowBloom\Interpolators\Interpolator;
use RowBloom\RowBloom\Renderers\Renderer;
use RowBloom\RowBloom\Renderers\Sizing\PaperFormat;
use RowBloom\RowBloom\RowBloom;
use RowBloom\RowBloom\Types\Table;

app()->get(RowBloom::class)
    ->addTable([
        ['title' => 'Title1', 'body' => 'body1'],
        ['title' => 'Title2', 'body' => 'body2'],
    ])
    ->addTable(Table::fromArray([
        ['title' => 'Title3', 'body' => 'body3'],
        ['title' => 'Title4', 'body' => 'body4'],
    ]))
    // ---------------------------
    // ->setInterpolator(Interpolator::Twig)
    // ->setTemplate('
    //     <h1>{{ title }}</h1>
    //     <p>Bold text</p>
    //     <div>{{ body }}</div>
    // ')
    // ---------------------------
    ->setInterpolator(Interpolator::Php)
    ->setTemplate('
        <h1><?= $title ?></h1>
        <p>Bold text</p>
        <div><?= $body ?></div>
    ')
    // ---------------------------
    ->setOption('perPage', 2)
    ->setOption('landscape', false)
    ->setOption('format', PaperFormat::FORMAT_A4)
    ->setOption('displayHeaderFooter', true)
    ->addCss('
        p {font-weight: bold;}
    ')
    ->addCss('
        div {color: red;}
    ')
    // ---------------------------
    // ->setRenderer(Renderer::Mpdf)
    // ->setOption('margin', '25.4 mm')
    // ->setOption('rawHeader', '{DATE j-m-Y}|center|right')
    // ->setOption('rawFooter', 'left|center|{PAGENO}/{nb}')
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
    ->save(__DIR__.'/foo.pdf');
```

After finishing the fluent build, the execution goes through three main steps:

1. Collect data.
2. Interpolate data into the given template and make the final HTML body.
3. Handle options and output the final HTML or PDF.

### Data

Provide data using `addTable` or `addTablePath`.

A driver will be picked automatically for each table path.

Available drivers:

- Spreadsheet (`xlsx`, `xls`, `xml`, `ods`, `slk`, `gnumeric`, `html`, `csv`).
- Json.
- Folder.
- Csv (todo).
- Db (todo).

### Template

Pick a driver using `setInterpolator`, and provide a template with `setTemplate` or `setTemplatePath`.

The available interpolators are:

- Twig.
- Php.
- Blade (todo).

All interpolators are available in `RowBloom\RowBloom\Interpolators\Interpolator` enum, and you can provide a custom one as long as you implement `RowBloom\RowBloom\InterpolatorContract`

### Rendering

Pick a driver using `setRenderer`, and optionally provide css with `addCss` or `addCssPath`.

The available renderers are:

- Html.
- Mpdf.
- Php chrome (experimental).
- Browsershot (experimental).

All renderers are available in `RowBloom\RowBloom\Renderers\Renderer` enum, and you can provide a custom one as long as you implement `RowBloom\RowBloom\RendererContract`

### Options

Each renderer has its own way of handling **margin**, **header**, **footer**, **paper size**, and more. This package tries to act as a wrapper and give the same output from the same options regardless of the rendering library.

> The hard part XD

The main options are the ones offered by the browser print UI.

![browser print options](./browser_print_options.png)

| Option                | type            | default  | Html | Mpdf | Php chrome |
| --------------------- | --------------- | -------- | ---- | ---- | ---------- |
| `perPage`             | `int`           | `null`   | ✔️ | ✔️ | ✔️       |
| `displayHeaderFooter` | `bool`          | `true`   | ❌   | ✔️ | ✔️       |
| `rawHeader`           | `string`        | `null`   | ❌   | ✔️ | ✔️       |
| `rawFooter`           | `string`        | `null`   | ❌   | ✔️ | ✔️       |
| `printBackground`     | `bool`          | `false`  | ❌   | ❌   | ✔️       |
| `preferCSSPageSize`   | `bool`          | `false`  | ❌   | ❌   | ✔️       |
| `landscape`           | `bool`          | `false`  | ❌   | ✔️ | ✔️       |
| `format`              | `PaperFormat`   | `null`   | ❌   | ✔️ | ✔️       |
| `width`               | `string`        | `null`   | ❌   | ✔️ | ✔️       |
| `height`              | `string`        | `null`   | ❌   | ✔️ | ✔️       |
| `margin`              | `array\|string` | `'1 in'` | ❌   | ✔️ | ✔️       |
| `metadataTitle`       | `string`        | `null`   | ❌   | ✔️ | ❌         |
| `metadataAuthor`      | `string`        | `null`   | ❌   | ✔️ | ❌         |
| `metadataCreator`     | `string`        | `null`   | ❌   | ✔️ | ❌         |
| `metadataSubject`     | `string`        | `null`   | ❌   | ✔️ | ❌         |
| `metadataKeywords`    | `string`        | `null`   | ❌   | ✔️ | ❌         |
|                       |                 |          |      |      |            |

### Support info

When I was building an application to consume this library, I found it useful to get lists of what is supported by `RowBloom` as drivers, file extensions, and options per rendering driver... So here comes `RowBloom\RowBloom\Support` class.

```php
use RowBloom\RowBloom\Support;

/** @var Support */
$support = app()->get(Support::class);

$support->getDataCollectorDrivers();
$support->getInterpolatorDrivers();
$support->getRendererDrivers();

$support->getSupportedTableFileExtensions();

$support->getRendererOptionsSupport('driverName');
```

### Config

`RowBloom\RowBloom\Config`

## Gotchas

- npm path
- node path
- Puppeteer installation.
- Chromium path.
- Web process permissions.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

![class diagram](./class_diagram.drawio.png)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
