<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Definition\ClassDefinition;
use Laganica\Di\Definition\DefinitionInterface;
use Laganica\Di\Exception\NotFoundException;

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

        if (!class_exists($class)) {
            NotFoundException::create($class);
        }

        $params = $this->getConstructorParams($class);

        return new $class(...$params);
    }
}
