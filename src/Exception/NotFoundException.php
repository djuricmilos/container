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
     * @return NotFoundException
     */
    public static function create(string $id): self
    {
        return new self("Definition for entry or class $id is not found");
    }
}
