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
