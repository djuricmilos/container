<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Definition\BindDefinition;
use Laganica\Di\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Class BindResolver
 *
 * @package Laganica\Di\Resolver
 */
class BindResolver extends ReflectionResolver
{
    /**
     * @param ContainerInterface $container
     * @param BindDefinition $definition
     *
     * @throws NotFoundException
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, BindDefinition $definition)
    {
        $class = $definition->getClass();
        $params = $this->getConstructorParams($container, $class);

        return new $class(...$params);
    }
}
