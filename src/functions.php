<?php

namespace Laganica\Di;

use Closure;
use Laganica\Di\Definition\AliasDefinition;
use Laganica\Di\Definition\ClassDefinition;
use Laganica\Di\Definition\ClosureDefinition;
use Laganica\Di\Definition\FactoryDefinition;
use Laganica\Di\Definition\ValueDefinition;

function bind(string $class): ClassDefinition
{
    return new ClassDefinition($class);
}

function value(string $value): ValueDefinition
{
    return new ValueDefinition($value);
}

function factory(string $class): FactoryDefinition
{
    return new FactoryDefinition($class);
}

function alias(string $class): AliasDefinition
{
    return new AliasDefinition($class);
}

function closure(Closure $closure): ClosureDefinition
{
    return new ClosureDefinition($closure);
}
