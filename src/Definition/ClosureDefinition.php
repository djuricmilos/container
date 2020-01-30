<?php

namespace Laganica\Di\Definition;

use Closure;

/**
 * Class ClosureDefinition
 *
 * @package Laganica\Di\Definition
 */
class ClosureDefinition implements DefinitionInterface
{
    /**
     * @var Closure
     */
    private $closure;

    /**
     * @param Closure $closure
     */
    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @return Closure
     */
    public function getClosure(): Closure
    {
        return $this->closure;
    }
}
