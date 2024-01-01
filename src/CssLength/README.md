# CSS Sizing

[![Latest Version on Packagist](https://img.shields.io/packagist/v/row-bloom/css-length.svg?style=flat-square)](https://packagist.org/packages/row-bloom/css-length)
[![Total Downloads](https://img.shields.io/packagist/dt/row-bloom/css-length.svg?style=flat-square)](https://packagist.org/packages/row-bloom/css-length)
[![Pest Action](https://img.shields.io/github/actions/workflow/status/row-bloom/row-bloom/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/row-bloom/row-bloom/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Pint action](https://img.shields.io/github/actions/workflow/status/row-bloom/row-bloom/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/row-bloom/row-bloom/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)

Value objects and enums to manipulate CSS lengths and ensure valid values in your PHP code.

> [!IMPORTANT]
> This is a sub-split, for development, pull requests and issues, visit: <https://github.com/row-bloom/row-bloom>

This package adheres to the [CSS Values and Units Module Level 3 Candidate Recommendation](https://www.w3.org/TR/css-values-3/#lengths), particularly focusing on length values. It also encompasses related elements like standard paper sizes and facets of the box model, providing cohesive support for length applications.

Notably, it proves particularly beneficial for tasks involving PDF generation from HTML.

## Installation

```bash
composer require row-bloom/css-length
```

## Requirements

- PHP 8.1

## Usage

### LengthUnit enum

- An enum of absolute and relative length units.
- Absolute units are `px`, `pc`, `pt`, `mm`, `cm`, `in`.
- Relative units (todo).
- Provides absolute units equivalence using `absoluteUnitsEquivalence` static method.

### Length object

- Create value object by parsing a dimension string or providing a pair of value and unit.

### BoxArea object

- Value object for representing Padding areas, Margin areas, and border areas.
- Create and object by providing a valid CSS value string.
- Access value of each of the for sides (`top`, `right`, `bottom`, `left`).

### BoxSize object

Encompasses a pair of width and height.

### PaperFormat enum

- ISO 216:2007 `A0`, `A1`, `A2`, `A3`, `A4`, `A5`, `A6`, `A7`, `A8`, `A9`, `A10`, `B0`, `B1`, `B2`, `B3`, `B4`, `B5`, `B6`, `B7`, `B8`, `B9`, `B10`.
- ISO 269:1985 `C0`, `C1`, `C2`, `C3`, `C4`, `C5`, `C6`, `C7`, `C8`, `C9`, `C10`.
- ASME Y14.1-2020 US Paper Sizes `LETTER`, `LEGAL`, `LEDGER`, `TABLOID`.

> Precede any official paper format name with an underscore to get the enum case.
