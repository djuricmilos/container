<?php

namespace Laganica\Di\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class NotFoundException
 *
 * @package Laganica\Di\Exception
 */
class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{
    /**
     * @param string $id
     *
     * @return self
     */
    public static function create(string $id): self
    {
        return new self("Not able to resolve $id entry");
    }
}
