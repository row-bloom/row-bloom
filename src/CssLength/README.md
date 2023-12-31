# CSS Sizing

[![Latest Version on Packagist](https://img.shields.io/packagist/v/row-bloom/css-length.svg?style=flat-square)](https://packagist.org/packages/row-bloom/css-length)
[![Total Downloads](https://img.shields.io/packagist/dt/row-bloom/css-length.svg?style=flat-square)](https://packagist.org/packages/row-bloom/css-length)
<!-- [![Pest Action](https://img.shields.io/github/actions/workflow/status/row-bloom/css-length/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/row-bloom/css-length/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Pint action](https://img.shields.io/github/actions/workflow/status/row-bloom/css-length/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/row-bloom/css-length/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain) -->

Value objects and enums to manipulate CSS sizing and ensure valid values in your PHP code.

> [!IMPORTANT]
> This is a sub-split, for development, pull requests and issues, visit: <https://github.com/row-bloom/row-bloom>

The premise of this package is focused on covering CSS3 sizing according to ... and extends covering some box models due to the high cohesion.

- <https://www.w3.org/TR/css-values-3/#lengths>
- <https://www.w3.org/TR/css-sizing-3/>
- <https://www.w3.org/TR/css-page-3/>

## Installation

```bash
composer require row-bloom/css-length
```

## Requirements

- PHP 8.1

## Usage

### PaperFormat enum

...

### LengthUnit enum

- An enum of absolute and relative length units.
- Absolute units are `px`, `pc`, `pt`, `mm`, `cm`, `in`.
- Relative units (todo).
- Provides absolute units equivalence using `absoluteUnitsEquivalence` static method.

### Length object

...

### BoxArea object

...

### BoxSize object

...
