<?php

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
