<?php

namespace Laganica\Di\Definition;

/**
 * Class Definition
 *
 * @package Laganica\Di\Definition
 */
abstract class Definition
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
