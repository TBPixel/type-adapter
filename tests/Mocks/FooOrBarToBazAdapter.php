<?php

namespace TBPixel\TypeAdapter\Tests\Mocks;

use TBPixel\TypeAdapter\Adaptable;

final class FooOrBarToBazAdapter implements Adaptable
{
    /**
     * @param Foo|Bar $resource
     */
    public function adapt($resource)
    {
        return new Baz($resource->name);
    }

    public function expects()
    {
        return [
            Foo::class,
            Bar::class
        ];
    }
}
