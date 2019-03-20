<?php

namespace TBPixel\TypeAdapter\Tests;

use PHPUnit\Framework\TestCase;
use TBPixel\TypeAdapter\Adapter;
use TBPixel\TypeAdapter\Adaptable;
use TBPixel\TypeAdapter\Tests\Mocks;

final class AdapterTest extends TestCase
{
    /**
     * @var \TBPixel\TypeAdapter\Adapter
     */
    private $adapter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adapter = new Adapter;
    }

    /** @test */
    public function can_adapt_from_foo_to_bar()
    {
        $f = new Mocks\Foo('bar');
        $bar = $this->adapter->adapt($f, new Mocks\FooToBarAdapter);

        $this->assertInstanceOf(Mocks\Bar::class, $bar);
        $this->assertEquals('bar', $bar->name);
    }

    /** @test */
    public function can_adapt_from_multi_type()
    {
        $f = new Mocks\Foo('foo');
        $b = new Mocks\Bar('bar');
        $baz1 = $this->adapter->adapt($f, new Mocks\FooOrBarToBazAdapter);
        $baz2 = $this->adapter->adapt($b, new Mocks\FooOrBarToBazAdapter);

        $this->assertInstanceOf(Mocks\Baz::class, $baz1);
        $this->assertEquals('foo', $baz1->name);

        $this->assertInstanceOf(Mocks\Baz::class, $baz2);
        $this->assertEquals('bar', $baz2->name);
    }

    /** @test */
    public function can_adapt_from_primitive()
    {
        $adapter = new class implements Adaptable {
            public function adapt($resource)
            {
                return new Mocks\Foo($resource);
            }

            public function expects()
            {
                return 'string';
            }
        };

        $foo = $this->adapter->adapt('foo', $adapter);

        $this->assertInstanceOf(Mocks\Foo::class, $foo);
        $this->assertEquals('foo', $foo->name);
    }

    /** @test */
    public function can_adapt_multi_primitive()
    {
        $adapter = new class implements Adaptable {
            public function adapt($resource)
            {
                return new Mocks\Foo($resource);
            }

            public function expects()
            {
                return [
                    'string',
                    'integer'
                ];
            }
        };

        $foo = $this->adapter->adapt(1, $adapter);

        $this->assertInstanceOf(Mocks\Foo::class, $foo);
        $this->assertEquals('1', $foo->name);
    }

    /** @test */
    public function cannot_adapt_invalid_to_multi()
    {
        $this->expectException(\InvalidArgumentException::class);

        $adapter = new class implements Adaptable {
            public function adapt($resource)
            {
                return new Mocks\Foo($resource);
            }

            public function expects()
            {
                return [
                    'string',
                    'integer'
                ];
            }
        };

        $this->adapter->adapt([], $adapter);
    }

    /** @test */
    public function cannot_adapt_from_a_null_resource()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->adapter->adapt(null, new Mocks\FooToBarAdapter);
    }

    /** @test */
    public function mixed_does_not_allow_null()
    {
        $this->expectException(\InvalidArgumentException::class);

        $adapter = new class implements Adaptable {
            public function adapt($resource)
            {
                return new Mocks\Foo($resource);
            }

            public function expects()
            {
                return 'mixed';
            }
        };

        $this->adapter->adapt(null, $adapter);
    }

    /** @test */
    public function cannot_adapt_from_an_invalid_resource()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->adapter->adapt(new Mocks\Baz('baz'), new Mocks\FooToBarAdapter);
    }
}
