<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Definition\DefinitionInterface;
use Laganica\Di\Definition\FactoryDefinition;
use Laganica\Di\Exception\ContainerException;
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
        $factoryInterface = FactoryInterface::class;
        $interfaces = class_implements($factoryClass);

        if (empty($interfaces) || !in_array($factoryInterface, $interfaces, true)) {
            throw new ContainerException("$factoryClass must implement $factoryInterface");
        }

        return (new $factoryClass)($this->getContainer());
    }
}
