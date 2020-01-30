<?php

namespace Laganica\Di\Resolver;

use Laganica\Di\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Class AutowireResolver
 *
 * @package Laganica\Di\Resolver
 */
class ClassResolver extends ReflectionResolver
{
    /**
     * @param ContainerInterface $container
     * @param string $id
     *
     * @throws NotFoundException
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, string $id)
    {
        if (class_exists($id)) {
            $params = $this->getConstructorParams($container, $id);

            return new $id(...$params);
        }

        throw NotFoundException::create($id);
    }
}
