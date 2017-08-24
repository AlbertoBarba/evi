[![Build Status](https://travis-ci.org/howyi/evi.svg?branch=master)](https://travis-ci.org/howyi/evi)
[![Coverage Status](https://coveralls.io/repos/github/howyi/evi/badge.svg?branch=master)](https://coveralls.io/github/howyi/evi?branch=master)
# evi
Extended YAML&JSON parser

# work
## normal-parse

```yaml
foo: 1
bar: 2
```
*converted array*
```php
[
  'foo' => 1,
  'bar' => 2,
]
```

## eval

```yaml
foo: env:$OS
bar: php:range(0, 3)
```
*converted array*
```php
[
  'foo' => 'Windows_NT',
  'bar' => [
      0,
      1,
      2,
      3,
  ],
]
```

## call

`self.yml`
```yaml
foo: 1
bar:
    $ref: ./callee.yml
```
`callee.yaml`
```yaml
hoge: 99
sushi: 22
```
*converted array*
```php
[
  'foo' => 1,
  'bar' => [
      'hoge' => 99,
      'sushi' => 22,
  ],
]
```

## inherit

`self.yml`
```yaml
$ext: ./parent.yml
foo: 1
bar: 2
```
`parent.yaml`
```yaml
hoge: 99
bar: 88
```
*converted array*
```php
[
  'foo' => 1,
  'bar' => 2,
  'hoge' => 99,
]
```
