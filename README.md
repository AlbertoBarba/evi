[![Build Status](https://travis-ci.org/howyi/evi.svg?branch=master)](https://travis-ci.org/howyi/evi)
[![Coverage Status](https://coveralls.io/repos/github/howyi/evi/badge.svg?branch=master)](https://coveralls.io/github/howyi/evi?branch=master)
# evi
Extended YAML&JSON parser

## wiki: https://github.com/howyi/evi/wiki

## Quickstart
### Install
`composer require howyi/evi`
### Parse
```php
$associativeArrays = Howyi\Evi::parse('foo/bar.yml');
$associativeArrays = Howyi\Evi::parse('foo/bar.json');
```
