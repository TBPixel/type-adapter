# Type Adapter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/TBPixel/type-adapter.svg?style=flat-square)](https://packagist.org/packages/tbpixel/type-adapter)
[![Build Status](https://img.shields.io/travis/TBPixel/type-adapter/master.svg?style=flat-square)](https://travis-ci.org/TBPixel/type-adapter)

A type-safe package for adapting from one data type to another.

## Contents

- [Installation](#installation)
- [Purpose](#purpose)
- [Examples](#examples)
- [Contributing](#contributing)
- [Changelog](#changelog)
- [Support Me](#support-me)
- [License](#license)

## Installation

Via composer:

```shell
composer require tbpixel/type-adapter
```

## Purpose

Have you ever needed to convert one instance (usually a [data transfer object](https://github.com/spatie/data-transfer-object)) from one type to another? I found myself writing static constructors like:

```php
class Foo
{
    /** @var string **/
    public $name;
}

class Bar
{
    /** @var string **/
    public $name;

    public static function fromFoo(Foo $foo): self
    {
        return new self($foo->name);
    }
}
```

In all honesty this is fine for a start, but it really creates tight coupling between objects in order to handle these constructor conversions. If I wanted to remove `Foo` entirely, I'd have to go and find every place where I called `fromFoo` on the `Bar` class! This can get even worse if the source type comes from a package; it may become critical to decouple this package from an application and yet references to it are now litered all through out _my_ application.

This why I created the Type Adapter package. This package allows for loosely coupled, type safe conversions without static constructors. It attempts to reduce coupling and act as a communcation converter between the layers of your application. It is particularly useful in hexagonal architected, and by extension domain driven application, software design.

## Examples

Taking the types from above, we could create an adapter that converts from `Foo` to `Bar` like so:

```php
use TBPixel\TypeAdapter\Adaptable;

class FooToBarAdapter implements Adaptable
{
    /**
     * Adapts a Foo resource into a Bar resource.
     *
     * @param Foo $resource
     *
     * @return Bar
     */
    public function adapt($resource)
    {
        return new Bar($resource->name);
    }

    /**
     * Returns the acceptable valid resource type as either a string or an array.
     *
     * @return array|string
     */
    public function expects()
    {
        return Foo::class;
    }
}
```

You can see a couple of things going on here. For starters, we implement the `Adaptable` interface defined in this package. This interface requires us to implement an `adapt($resource)` method and a `expects()` method.

The `adapt` method accepts in the resource, which we can type hint using a doc block comment, and then returns the converted type.

The `expects` method is for type safety. It defines out a validation rule for the `adapt` method to expect. This is what allows us type safety.

The `expects` method can either return a `string` type name or an `array` of strings, for variadic types.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

### Support Me

Hi! I'm a developer living in Vancouver, BC and boy is the housing market tough. If you wanna support me consider following me on [Twitter @TBPixel](https://twitter.com/TBPixel), or maybe [buying me a coffee](https://ko-fi.com/tbpixel). Thanks!

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
