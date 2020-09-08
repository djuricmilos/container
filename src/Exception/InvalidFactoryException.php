<?php

/**
 * This file is part of the Container package.
 *
 * Copyright (c) Miloš Đurić <djuric.milos@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

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
