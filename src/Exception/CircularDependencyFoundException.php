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
