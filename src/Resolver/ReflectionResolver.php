<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Exception\NotFoundException;
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
     * @throws NotFoundException
     *
     * @return array
     */
    protected function getConstructorParams(string $class): array
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
            $params[] = $this->getContainer()->get($dependencyId);
        }

        return $params;
    }
}
