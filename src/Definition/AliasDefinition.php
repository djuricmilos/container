<?php

namespace Laganica\Di\Definition;

/**
 * Class AliasDefinition
 *
 * @package Laganica\Di\Definition
 */
class AliasDefinition implements DefinitionInterface
{
    /**
     * @var string
     */
    private $alias;

    /**
     * @param string $alias
     */
    public function __construct(string $alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }
}
