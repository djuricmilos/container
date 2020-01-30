<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

/**
 * Class ReflectionResolver
 *
 * @package Laganica\Di\Resolver
 */
abstract class ReflectionResolver
{
    /**
     * @param ContainerInterface $container
     * @param string $class
     *
     * @throws NotFoundException
     *
     * @return array
     */
    protected function getConstructorParams(ContainerInterface $container, string $class): array
    {
        $params = [];

        try {
            $reflection = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw NotFoundException::create($class);
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $params;
        }

        foreach ($constructor->getParameters() AS $param) {
            $dependencyId = $param->getClass()->name;
            $params[] = $container->get($dependencyId);
        }

        return $params;
    }
}
