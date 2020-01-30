<?php

namespace Laganica\Di\Definition;

/**
 * Class ValueDefinition
 *
 * @package Laganica\Di\Definition
 */
class ValueDefinition
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
