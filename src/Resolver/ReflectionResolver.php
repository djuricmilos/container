<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Exception\ClassNotFoundException;
use ReflectionClass;
use ReflectionException;

/**
 * Class ReflectionResolver
 *
 * @package Laganica\Di\Resolver
 */
abstract class ReflectionResolver extends Resolver
{
    /**
     * @param string $class
     *
     * @throws ClassNotFoundException
     *
     * @return array
     */
    protected function getConstructorParams(string $class): array
    {
        $params = [];

        try {
            $reflection = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw ClassNotFoundException::create($class);
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $params;
        }

        foreach ($constructor->getParameters() AS $param) {
            $dependencyId = $param->getClass()->name;
            $params[] = $this->getContainer()->get($dependencyId);
        }

        return $params;
    }
}
