<?php

namespace Laganica\Di\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class CircularDependencyFoundException
 *
 * @package Laganica\Di\Exception
 */
class CircularDependencyFoundException extends ContainerException implements NotFoundExceptionInterface
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
