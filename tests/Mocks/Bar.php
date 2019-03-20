<?php declare(strict_types=1);

namespace TBPixel\TypeAdapter\Tests\Mocks;

final class Bar
{
    /** @var string */
    public $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
