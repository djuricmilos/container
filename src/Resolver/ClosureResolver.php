<?php

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
