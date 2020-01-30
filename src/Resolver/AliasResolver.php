<?php

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
