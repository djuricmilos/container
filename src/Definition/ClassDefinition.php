<?php

namespace Laganica\Di\Definition;

/**
 * Class ClassDefinition
 *
 * @package Laganica\Di\Definition
 */
class ClassDefinition implements DefinitionInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
