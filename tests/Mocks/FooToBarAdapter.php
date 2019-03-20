<?php declare(strict_types=1);

namespace TBPixel\TypeAdapter\Tests\Mocks;

use TBPixel\TypeAdapter\Adaptable;

final class FooToBarAdapter implements Adaptable
{
    /**
     * @param Foo $resource
     *
     * @return Bar
     */
    public function adapt($resource)
    {
        return new Bar($resource->name);
    }

    public function expects()
    {
        return Foo::class;
    }
}
