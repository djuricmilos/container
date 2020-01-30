<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Definition\ValueDefinition;
use Psr\Container\ContainerInterface;

/**
 * Class ValueResolver
 *
 * @package Laganica\Di\Resolver
 */
class ValueResolver extends ReflectionResolver
{
    /**
     * @param ContainerInterface $container
     * @param ValueDefinition $definition
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, ValueDefinition $definition)
    {
        return $definition->getValue();
    }
}
