<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Exception\ClassNotFoundException;
use PhpDocReader\AnnotationException;
use PhpDocReader\PhpDocReader;
use phpDocumentor\Reflection\DocBlockFactory;
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
        $docBlockFactory = DocBlockFactory::createInstance();

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->isStatic()) {
                continue;
            }

            $docBlock = $docBlockFactory->create($reflectionProperty->getDocComment());

            if (!$docBlock->hasTag('Inject')) {
                continue;
            }

            try {
                $identifier = $phpDocReader->getPropertyClass($reflectionProperty);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($entry, $this->getContainer()->get($identifier));
            } catch (AnnotationException $ex) {
                throw new ClassNotFoundException($ex->getMessage());
            }
        }
    }
}
