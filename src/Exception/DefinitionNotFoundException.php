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

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class DefinitionNotFoundException
 *
 * @package Laganica\Di\Exception
 */
class DefinitionNotFoundException extends ContainerException implements NotFoundExceptionInterface
{
    /**
     * @param string $id
     *
     * @return self
     */
    public static function create(string $id): self
    {
        return new self("Definition for entry or class $id is not found");
    }
}
