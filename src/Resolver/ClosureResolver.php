<?php

/**
 * This file is part of the Container package.
 *
 * Copyright (c) Miloš Đurić <djuric.milos@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Laganica\Di\Resolver;

use Laganica\Di\Definition\ClosureDefinition;
use Laganica\Di\Definition\DefinitionInterface;

/**
 * Class ClosureResolver
 *
 * @package Laganica\Di\Resolver
 */
class ClosureResolver extends Resolver
{
    /**
     * @inheritDoc
     */
    public function resolve(DefinitionInterface $definition)
    {
        $this->validate($definition, ClosureDefinition::class);
        $closure = $definition->getClosure();

        return $closure($this->getContainer());
    }
}
