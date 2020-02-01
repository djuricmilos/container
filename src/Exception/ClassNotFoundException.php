<?php

namespace Laganica\Di\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class ClassNotFoundException
 *
 * @package Laganica\Di\Exception
 */
class ClassNotFoundException extends ContainerException implements NotFoundExceptionInterface
{
    /**
     * @param string $class
     *
     * @return self
     */
    public static function create(string $class): self
    {
        return new self("$class class not found");
    }
}
