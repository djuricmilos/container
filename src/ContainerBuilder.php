<?php

namespace Laganica\Di;

use Laganica\Di\Definition\DefinitionFactory;
use Laganica\Di\Resolver\ResolverFactory;
use Psr\Container\ContainerInterface;

/**
 * Class ContainerBuilder
 *
 * @package Laganica\Di
 */
class ContainerBuilder
{
    /**
     * @var bool
     */
    private $autowire = true;

    /**
     * @param bool $autowire
     */
    public function setAutowire(bool $autowire): void
    {
        $this->autowire = $autowire;
    }

    public function isAutowire(): bool
    {
        return $this->autowire;
    }

    /**
     * @return ContainerInterface
     */
    public function build(): ContainerInterface
    {
        $container = new Container(new DefinitionFactory(), new ResolverFactory());
        $container->setAutowire($this->isAutowire());

        return $container;
    }
}
