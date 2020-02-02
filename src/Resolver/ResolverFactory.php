<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Definition\AliasDefinition;
use Laganica\Di\Definition\ClassDefinition;
use Laganica\Di\Definition\ClosureDefinition;
use Laganica\Di\Definition\DefinitionInterface;
use Laganica\Di\Definition\FactoryDefinition;
use Laganica\Di\Definition\ValueDefinition;
use Laganica\Di\Exception\InvalidDefinitionException;
use Psr\Container\ContainerInterface;

/**
 * Class ResolverFactory
 *
 * @package Laganica\Di\Resolver
 */
class ResolverFactory implements ResolverFactoryInterface
{
    private static $classMap = [
        ClassDefinition::class => ClassResolver::class,
        FactoryDefinition::class => FactoryResolver::class,
        ValueDefinition::class => ValueResolver::class,
        AliasDefinition::class => AliasResolver::class,
        ClosureDefinition::class => ClosureResolver::class,
    ];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ResolverInterface[]
     */
    private $resolvers = [];

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function create(DefinitionInterface $definition): ResolverInterface
    {
        $resolverClass = self::$classMap[get_class($definition)] ?? null;

        if ($resolverClass === null) {
            throw InvalidDefinitionException::create($definition);
        }

        return $this->resolvers[$resolverClass]
            ?? $this->resolvers[$resolverClass] = new $resolverClass($this->container);
    }
}
