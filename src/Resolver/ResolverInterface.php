<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Definition\DefinitionInterface;
use Laganica\Di\Exception\ContainerException;
use Laganica\Di\Exception\NotFoundException;

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
     * @throws NotFoundException
     *
     * @return mixed
     */
    public function resolve(DefinitionInterface $definition);
}
