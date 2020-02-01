<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Definition\DefinitionInterface;
use Laganica\Di\Definition\FactoryDefinition;
use Laganica\Di\Exception\ClassNotFoundException;
use Laganica\Di\Exception\InvalidFactoryException;
use Laganica\Di\FactoryInterface;

/**
 * Class FactoryResolver
 *
 * @package Laganica\Di\Resolver
 */
class FactoryResolver extends Resolver
{
    /**
     * @inheritDoc
     */
    public function resolve(DefinitionInterface $definition)
    {
        $this->validate($definition, FactoryDefinition::class);

        $factoryClass = $definition->getFactoryClass();

        if (!class_exists($factoryClass)) {
            throw ClassNotFoundException::create($factoryClass);
        }

        $factoryInterface = FactoryInterface::class;
        $interfaces = class_implements($factoryClass);

        if (empty($interfaces) || !in_array($factoryInterface, $interfaces, true)) {
            throw InvalidFactoryException::create($factoryClass, $factoryInterface);
        }

        return (new $factoryClass)($this->getContainer());
    }
}
