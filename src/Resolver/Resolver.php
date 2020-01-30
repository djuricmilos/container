<?php

namespace Laganica\Di\Resolver;

use InvalidArgumentException;
use Laganica\Di\Definition\DefinitionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class Resolver
 *
 * @package Laganica\Di\Resolver
 */
abstract class Resolver implements ResolverInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Resolver constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param DefinitionInterface $definition
     * @param string $expectedDefinitionClass
     */
    protected function validate(DefinitionInterface $definition, string $expectedDefinitionClass): void
    {
        if (!$definition instanceof $expectedDefinitionClass) {
            $actualType = get_class($definition);

            throw new InvalidArgumentException("Argument \$definition must be $expectedDefinitionClass, $actualType given");
        }
    }
}
