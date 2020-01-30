<?php

namespace Laganica\Di\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class DefinitionNotFoundException
 *
 * @package Laganica\Di\Exception
 */
class DefinitionNotFoundException extends ContainerException implements NotFoundExceptionInterface
{
    /**
     * @param string $id
     *
     * @return self
     */
    public static function create(string $id): self
    {
        return new self("Definition for entry or class $id is not found");
    }
}
