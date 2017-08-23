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
    call: ./callee.yml
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
inherit: ./parent.yml
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
