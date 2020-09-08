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

use Laganica\Di\Definition\ClassDefinition;
use Laganica\Di\Definition\DefinitionInterface;

/**
 * Class AutowireResolver
 *
 * @package Laganica\Di\Resolver
 */
class ClassResolver extends ReflectionResolver
{
    /**
     * @inheritDoc
     */
    public function resolve(DefinitionInterface $definition)
    {
        $this->validate($definition, ClassDefinition::class);

        $class = $definition->getClass();
        $params = $this->getConstructorParams($class);

        $entry = new $class(...$params);

        if ($this->getContainer()->hasAnnotationsEnabled()) {
            $this->injectProperties($entry);
        }

        return $entry;
    }
}
