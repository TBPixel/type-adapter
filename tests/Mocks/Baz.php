<?php

namespace TBPixel\TypeAdapter\Tests\Mocks;

final class Baz
{
    /** @var string */
    public $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
