<?php

namespace Laganica\Di;

use Laganica\Di\Definition\AliasDefinition;
use Laganica\Di\Definition\BindDefinition;
use Laganica\Di\Definition\FactoryDefinition;
use Laganica\Di\Definition\ValueDefinition;

function bind(string $class): BindDefinition
{
    return new BindDefinition($class);
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
