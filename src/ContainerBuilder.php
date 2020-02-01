<?php

namespace Laganica\Di;

use Laganica\Di\Definition\DefinitionFactory;
use Laganica\Di\Resolver\ResolverFactory;

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

    /**
     * @return bool
     */
    private function isAutowire(): bool
    {
        return $this->autowire;
    }

    /**
     * @return Container
     */
    public function build(): Container
    {
        $container = new Container(new DefinitionFactory(), new ResolverFactory());
        $container->setAutowire($this->isAutowire());

        return $container;
    }
}
