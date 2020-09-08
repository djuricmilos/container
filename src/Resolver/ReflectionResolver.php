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

use Laganica\Di\Exception\ClassNotFoundException;
use PhpDocReader\AnnotationException;
use PhpDocReader\PhpDocReader;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

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

    /**
     * @param object $entry
     *
     * @throws ClassNotFoundException
     *
     * @return void
     */
    protected function injectProperties(object $entry): void
    {
        $class = get_class($entry);

        try {
            $reflectionClass = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw ClassNotFoundException::create($class);
        }

        $phpDocReader = new PhpDocReader();

        foreach ($this->getInjectableProperties($reflectionClass) as $property) {
            try {
                $identifier = $phpDocReader->getPropertyClass($property);
                $property->setAccessible(true);
                $property->setValue($entry, $this->getContainer()->get($identifier));
            } catch (AnnotationException $ex) {
                throw new ClassNotFoundException($ex->getMessage());
            }
        }
    }

    /**
     * @param ReflectionClass $class
     *
     * @return ReflectionProperty[]
     */
    private function getInjectableProperties(ReflectionClass $class): array
    {
        $properties = [];
        $docBlockFactory = DocBlockFactory::createInstance();

        foreach ($class->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $docBlock = $docBlockFactory->create($property->getDocComment());

            if (!$docBlock->hasTag('Inject')) {
                continue;
            }

            $properties[] = $property;
        }

        return $properties;
    }
}
