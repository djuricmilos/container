<?php

namespace Laganica\Di\Definition;

/**
 * Class ValueDefinition
 *
 * @package Laganica\Di\Definition
 */
class ValueDefinition implements DefinitionInterface
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
