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
