<?php

namespace Laganica\Di\Exception;

/**
 * Class CircularDependencyFoundException
 *
 * @package Laganica\Di\Exception
 */
class CircularDependencyFoundException extends ContainerException
{
    /**
     * @param string $id
     *
     * @return self
     */
    public static function create(string $id): self
    {
        return new self("Circular dependency found for entry or class $id");
    }
}
