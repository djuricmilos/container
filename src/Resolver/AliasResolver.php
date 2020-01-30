<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Definition\AliasDefinition;
use Psr\Container\ContainerInterface;

/**
 * Class AliasResolver
 *
 * @package Laganica\Di\Resolver
 */
class AliasResolver extends ReflectionResolver
{
    /**
     * @param ContainerInterface $container
     * @param AliasDefinition $definition
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, AliasDefinition $definition)
    {
        return $container->get($definition->getAlias());
    }
}
