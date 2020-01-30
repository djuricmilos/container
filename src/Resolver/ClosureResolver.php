<?php

namespace Laganica\Di\Resolver;

use Closure;
use Psr\Container\ContainerInterface;

/**
 * Class ClosureResolver
 *
 * @package Laganica\Di\Resolver
 */
class ClosureResolver
{
    /**
     * @param ContainerInterface $container
     * @param Closure $definition
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, Closure $definition)
    {
        return $definition($container);
    }
}
