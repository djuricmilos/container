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

use InvalidArgumentException;
use Laganica\Di\Container;
use Laganica\Di\Definition\DefinitionInterface;

/**
 * Class Resolver
 *
 * @package Laganica\Di\Resolver
 */
abstract class Resolver implements ResolverInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Container
     */
    protected function getContainer(): Container
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
