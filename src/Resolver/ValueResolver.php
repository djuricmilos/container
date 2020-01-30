<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Definition\DefinitionInterface;
use Laganica\Di\Definition\ValueDefinition;

/**
 * Class ValueResolver
 *
 * @package Laganica\Di\Resolver
 */
class ValueResolver extends Resolver
{
    /**
     * @inheritDoc
     */
    public function resolve(DefinitionInterface $definition)
    {
        $this->validate($definition, ValueDefinition::class);

        return $definition->getValue();
    }
}
