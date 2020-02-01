<?php

namespace Laganica\Di\Exception;

/**
 * Class ClassNotFoundException
 *
 * @package Laganica\Di\Exception
 */
class ClassNotFoundException extends ContainerException
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
