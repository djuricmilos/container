<?php

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
