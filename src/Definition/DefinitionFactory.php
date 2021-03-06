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

use Closure;
use Laganica\Di\Exception\InvalidDefinitionException;

/**
 * Class DefinitionFactory
 *
 * @package Laganica\Di\Definition
 */
class DefinitionFactory implements DefinitionFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create($definition): DefinitionInterface
    {
        if ($definition instanceof DefinitionInterface) {
            return $definition;
        }

        if ($definition instanceof Closure) {
            return new ClosureDefinition($definition);
        }

        if (is_string($definition)) {
            return new ClassDefinition($definition);
        }

        throw InvalidDefinitionException::create($definition);
    }
}
