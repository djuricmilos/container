<?php

/**
 * This file is part of the Container package.
 *
 * Copyright (c) Miloš Đurić <djuric.milos@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Laganica\Di\Definition;

/**
 * Class FactoryDefinition
 *
 * @package Laganica\Di\Definition
 */
class FactoryDefinition implements DefinitionInterface
{
    /**
     * @var string
     */
    private $factoryClass;

    /**
     * @param string $factoryClass
     */
    public function __construct(string $factoryClass)
    {
        $this->factoryClass = $factoryClass;
    }

    /**
     * @return string
     */
    public function getFactoryClass(): string
    {
        return $this->factoryClass;
    }
}
