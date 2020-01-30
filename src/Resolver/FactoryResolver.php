<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Definition\FactoryDefinition;
use Laganica\Di\Exception\ContainerException;
use Laganica\Di\FactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * Class FactoryResolver
 *
 * @package Laganica\Di\Resolver
 */
class FactoryResolver
{
    /**
     * @param ContainerInterface $container
     * @param FactoryDefinition $definition
     *
     * @throws ContainerException
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, FactoryDefinition $definition)
    {
        $factoryClass = $definition->getClass();
        $factoryInterface = FactoryInterface::class;
        $interfaces = class_implements($factoryClass);

        if (!$interfaces || !in_array($factoryInterface, $interfaces, true)) {
            throw new ContainerException("$factoryClass must implement $factoryInterface");
        }

        return (new $factoryClass)($container);
    }
}
