<?php

namespace TBPixel\TypeAdapter;

interface Adaptable
{
    /**
     * Convert one type into another. An implementor will be expected to receive their own input and return their own output.
     *
     * @param mixed $resource The resource to be adapted.
     *
     * @return mixed
     */
    public function adapt($resource);

    /**
     * The primitive type(s) and/or FQCN(s) to validate against before passing to the adapt method.
     *
     * @return string|array
     */
    public function expects();
}
