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
use Laganica\Di\Exception\ContainerException;
use Laganica\Di\Exception\ClassNotFoundException;

/**
 * Interface ResolverInterface
 *
 * @package Laganica\Di\Resolver
 */
interface ResolverInterface
{
    /**
     * @param DefinitionInterface $definition
     *
     * @throws ContainerException
     * @throws ClassNotFoundException
     *
     * @return mixed
     */
    public function resolve(DefinitionInterface $definition);
}
