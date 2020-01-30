<?php

namespace Laganica\Di;

use Psr\Container\ContainerInterface;

/**
 * Interface FactoryInterface
 *
 * @package Laganica\Di
 */
interface FactoryInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container);
}
