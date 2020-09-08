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

use Closure;
use Laganica\Di\Definition\DefinitionInterface;

/**
 * Class InvalidDefinitionException
 *
 * @package Laganica\Di\Exception
 */
class InvalidDefinitionException extends ContainerException
{
    /**
     * @param $definition
     *
     * @return self
     */
    public static function create($definition): self
    {
        return new self(vsprintf('Argument $definition must be either %s, %s or %s, %s given', [
            DefinitionInterface::class,
            Closure::class,
            'string',
            is_object($definition) ? get_class($definition) : gettype($definition)
        ]));
    }
}
