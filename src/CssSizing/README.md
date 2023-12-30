# CSS Sizing

[![Latest Version on Packagist](https://img.shields.io/packagist/v/row-bloom/css-sizing.svg?style=flat-square)](https://packagist.org/packages/row-bloom/css-sizing)
[![Total Downloads](https://img.shields.io/packagist/dt/row-bloom/css-sizing.svg?style=flat-square)](https://packagist.org/packages/row-bloom/css-sizing)
<!-- [![Pest Action](https://img.shields.io/github/actions/workflow/status/row-bloom/css-sizing/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/row-bloom/css-sizing/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Pint action](https://img.shields.io/github/actions/workflow/status/row-bloom/css-sizing/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/row-bloom/css-sizing/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain) -->

Value objects and enums to manipulate CSS sizing and ensure valid values in your PHP code.

> [!IMPORTANT]
> This is a sub-split, for development, pull requests and issues, visit: <https://github.com/row-bloom/row-bloom>

## Installation

```bash
composer require row-bloom/css-sizing
```

## Requirements

- PHP 8.1

## Usage

### Standard paper formats

`RowBloom\CssSizing\PaperFormat` enum.

### CSS absolute/relative length units

`RowBloom\CssSizing\LengthUnit` enum.

### CSS length manipulation and validation

`RowBloom\CssSizing\Length` object.

### CSS box model areas (Margin/Border/Padding)

`RowBloom\CssSizing\BoxArea` object.

### CSS box model sizing (Content/Box)

`RowBloom\CssSizing\BoxSize` object.
