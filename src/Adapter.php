<?php

namespace TBPixel\TypeAdapter;

final class Adapter
{
    /**
     * Adapts the given input resource into the adapted output resource.
     *
     * @param mixed $resource The resource to adapt to a new type.
     *
     * @throws \InvalidArgumentException Invalid resources cannot be adapted.
     *
     * @return mixed
     */
    public function adapt($resource, Adaptable $adapter)
    {
        $this->validate($resource, $adapter);

        return $adapter->adapt($resource);
    }

    private function validate($resource, Adaptable $adapter)
    {
        if ($resource === null) {
            throw new \InvalidArgumentException('a null resource cannot be adapted.');
        }

        $type = gettype($resource);
        $expects = $adapter->expects();

        if ($type === 'object') {
            $type = get_class($resource);
        }

        if (!is_array($expects)) {
            $err = $this->isValid($type, $expects);

            if ($err !== null) {
                throw $err;
            }

            return;
        }

        $err = null;

        foreach ($expects as $v) {
            $err = $this->isValid($type, $v);

            if ($err === null) {
                return;
            }
        }

        if ($err !== null) {
            throw $err;
        }
    }

    private function isValid(string $type, string $expects) : ?\Throwable
    {
        // Mixed can allow any
        if ($expects === 'mixed') {
            return null;
        }

        if ($type !== $expects) {
            return new \InvalidArgumentException("adapter expected type of `{$expects}`, instead got `{$type}`.");
        }

        return null;
    }
}
