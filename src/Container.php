<?php

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
    private $autowire;

    /**
     * @var bool
     */
    private $annotations;

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
     * @param bool $autowire
     */
    public function setAutowire(bool $autowire): void
    {
        $this->autowire = $autowire;
    }

    private function isAutowireEnabled(): bool
    {
        return $this->autowire;
    }

    /**
     * @param bool $annotations
     */
    public function setAnnotations(bool $annotations): void
    {
        $this->annotations = $annotations;
    }

    /**
     * @return bool
     */
    public function areAnnotationsEnabled(): bool
    {
        return $this->annotations;
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

        if ($definition === null && $this->isAutowireEnabled()) {
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
