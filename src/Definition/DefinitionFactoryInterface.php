<?php

/**
 * This file is part of the Container package.
 *
 * Copyright (c) Miloš Đurić <djuric.milos@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Laganica\Di\Definition;

use Laganica\Di\Exception\InvalidDefinitionException;

/**
 * Interface DefinitionFactoryInterface
 *
 * @package Laganica\Di\Definition
 */
interface DefinitionFactoryInterface
{
    /**
     * @param $definition
     *
     * @throws InvalidDefinitionException
     *
     * @return DefinitionInterface
     */
    public function create($definition): DefinitionInterface;
}
