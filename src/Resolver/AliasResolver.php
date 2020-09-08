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

use Laganica\Di\Definition\AliasDefinition;
use Laganica\Di\Definition\DefinitionInterface;

/**
 * Class AliasResolver
 *
 * @package Laganica\Di\Resolver
 */
class AliasResolver extends ReflectionResolver
{
    /**
     * @inheritDoc
     */
    public function resolve(DefinitionInterface $definition)
    {
        $this->validate($definition, AliasDefinition::class);

        return $this->getContainer()->get($definition->getAlias());
    }
}
