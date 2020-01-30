<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Definition\DefinitionInterface;
use Laganica\Di\Exception\InvalidDefinitionException;
use Psr\Container\ContainerInterface;

/**
 * Interface ResolverFactoryInterface
 *
 * @package Laganica\Di\Resolver
 */
interface ResolverFactoryInterface
{
    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container): void;

    /**
     * @param DefinitionInterface $definition
     *
     * @throws InvalidDefinitionException
     *
     * @return ResolverInterface
     */
    public function create(DefinitionInterface $definition): ResolverInterface;
}
