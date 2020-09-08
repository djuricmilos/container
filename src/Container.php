<?php

/**
 * This file is part of the Container package.
 *
 * Copyright (c) Miloš Đurić <djuric.milos@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Laganica\Di;

use ArrayObject;
use InvalidArgumentException;
use Laganica\Di\Definition\ClassDefinition;
use Laganica\Di\Definition\DefinitionFactoryInterface;
use Laganica\Di\Definition\DefinitionInterface;
use Laganica\Di\Exception\CircularDependencyFoundException;
use Laganica\Di\Exception\ContainerException;
use Laganica\Di\Exception\DefinitionNotFoundException;
use Laganica\Di\Exception\InvalidDefinitionException;
use Laganica\Di\Exception\ClassNotFoundException;
use Laganica\Di\Resolver\ResolverFactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class Container
 *
 * @package Laganica\Di
 */
class Container implements ContainerInterface
{
    /**
     * @var ArrayObject
     */
    private $definitions;

    /**
     * @var ArrayObject
     */
    private $entries;

    /**
     * @var ArrayObject
     */
    private $resolving;

    /**
     * @var bool
     */
    private $useAutowiring;

    /**
     * @var bool
     */
    private $useAnnotations;

    /**
     * @var ResolverFactoryInterface
     */
    private $resolverFactory;

    /**
     * @var DefinitionFactoryInterface
     */
    private $definitionFactory;

    /**
     * @param DefinitionFactoryInterface $definitionFactory
     * @param ResolverFactoryInterface $resolverFactory
     */
    public function __construct(DefinitionFactoryInterface $definitionFactory, ResolverFactoryInterface $resolverFactory)
    {
        $this->definitions = new ArrayObject();
        $this->entries = new ArrayObject();
        $this->resolving = new ArrayObject();
        $this->definitionFactory = $definitionFactory;
        $this->resolverFactory = $resolverFactory;
        $this->resolverFactory->setContainer($this);
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        if (!is_string($id)) {
            $type = is_object($id) ? get_class($id) : gettype($id);
            throw new InvalidArgumentException("Argument \$id must be string, $type given");
        }

        if ($this->entries->offsetExists($id)) {
            return $this->entries->offsetGet($id);
        }

        $entry = $this->resolveEntry($id);
        $this->entries->offsetSet($id, $entry);

        return $entry;
    }

    /**
     * Creates new entry by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while creating the entry.
     *
     * @return mixed Entry.
     */
    public function make(string $id)
    {
        return $this->resolveEntry($id);
    }

    /**
     * @inheritDoc
     */
    public function has($id): bool
    {
        try {
            $this->get($id);

            return true;
        } catch (NotFoundExceptionInterface $ex) {
            return false;
        } catch (ContainerExceptionInterface $ex) {
            return true;
        }
    }

    /**
     * @param string $id
     *
     * @throws CircularDependencyFoundException
     * @throws ClassNotFoundException
     * @throws ContainerException
     * @throws DefinitionNotFoundException
     * @throws InvalidDefinitionException
     *
     * @return mixed
     */
    private function resolveEntry(string $id)
    {
        $definition = $this->getDefinition($id);

        $this->startResolving($id);
        $entry = $this->resolverFactory->create($definition)->resolve($definition);
        $this->endResolving($id);

        return $entry;
    }

    /**
     * @param bool $useAutowiring
     */
    public function useAutowiring(bool $useAutowiring): void
    {
        $this->useAutowiring = $useAutowiring;
    }

    /**
     * @return bool
     */
    private function hasAutowiringEnabled(): bool
    {
        return $this->useAutowiring;
    }

    /**
     * @param bool $useAnnotations
     */
    public function useAnnotations(bool $useAnnotations): void
    {
        $this->useAnnotations = $useAnnotations;
    }

    /**
     * @return bool
     */
    public function hasAnnotationsEnabled(): bool
    {
        return $this->useAnnotations;
    }

    /**
     * @param ArrayObject $definitions
     */
    public function setDefinitions(ArrayObject $definitions): void
    {
        $this->definitions = $definitions;
    }

    /**
     * @param string $id
     *
     * @throws DefinitionNotFoundException
     * @throws InvalidDefinitionException
     *
     * @return DefinitionInterface
     */
    private function getDefinition(string $id): DefinitionInterface
    {
        $definition = $this->definitions->offsetExists($id)
            ? $this->definitions->offsetGet($id)
            : null;

        if ($definition === null && $this->hasAutowiringEnabled()) {
            $definition = new ClassDefinition($id);
        }

        if ($definition === null) {
            throw DefinitionNotFoundException::create($id);
        }

        return $this->definitionFactory->create($definition);
    }

    /**
     * @param string $id
     *
     * @throws CircularDependencyFoundException
     *
     * @return void
     */
    private function startResolving(string $id): void
    {
        if ($this->resolving->offsetExists($id)) {
            throw CircularDependencyFoundException::create($id);
        }

        $this->resolving->offsetSet($id, true);
    }

    /**
     * @param string $id
     *
     * @return void
     */
    private function endResolving(string $id): void
    {
        if ($this->resolving->offsetExists($id)) {
            $this->resolving->offsetUnset($id);
        }
    }
}
