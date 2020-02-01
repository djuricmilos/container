<?php

namespace Laganica\Di\Exception;

/**
 * Class InvalidFactoryException
 *
 * @package Laganica\Di\Exception
 */
class InvalidFactoryException extends ContainerException
{
    /**
     * @param string $factoryClass
     * @param string $factoryInterface
     *
     * @return self
     */
    public static function create(string $factoryClass, string $factoryInterface): self
    {
        return new self("$factoryClass must implement $factoryInterface");
    }
}
