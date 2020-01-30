<?php

namespace Laganica\Di\Definition;

use Laganica\Di\Exception\InvalidDefinitionException;

/**
 * Interface DefinitionFactoryInterface
 *
 * @package Laganica\Di\Definition
 */
interface DefinitionFactoryInterface
{
    /**
     * @param $definition
     *
     * @throws InvalidDefinitionException
     *
     * @return DefinitionInterface
     */
    public function create($definition): DefinitionInterface;
}
