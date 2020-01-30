<?php

namespace Laganica\Di;

use Closure;
use InvalidArgumentException;
use Laganica\Di\Definition\AliasDefinition;
use Laganica\Di\Definition\BindDefinition;
use Laganica\Di\Definition\FactoryDefinition;
use Laganica\Di\Definition\ValueDefinition;
use Laganica\Di\Exception\ContainerException;
use Laganica\Di\Exception\NotFoundException;
use Laganica\Di\Resolver\AliasResolver;
use Laganica\Di\Resolver\ClassResolver;
use Laganica\Di\Resolver\BindResolver;
use Laganica\Di\Resolver\ClosureResolver;
use Laganica\Di\Resolver\FactoryResolver;
use Laganica\Di\Resolver\ValueResolver;
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
     * @var array
     */
    private $definitions = [];

    /**
     * @var array
     */
    private $entries = [];

    /**
     * @var bool
     */
    private $autowire = true;

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        if (!is_string($id)) {
            $type = gettype($id);
            throw new InvalidArgumentException("Argument \$id must be string, $type given");
        }

        if (array_key_exists($id, $this->entries)) {
            return $this->entries[$id];
        }

        if (array_key_exists($id, $this->definitions)) {
            $definition = $this->definitions[$id];

            switch (true) {
                case $definition instanceof BindDefinition:
                    return $this->addEntry($id, (new BindResolver)($this, $definition));

                case $definition instanceof FactoryDefinition:
                    return $this->addEntry($id, (new FactoryResolver)($this, $definition));

                case $definition instanceof ValueDefinition:
                    return $this->addEntry($id, (new ValueResolver())($this, $definition));

                case $definition instanceof AliasDefinition:
                    return $this->addEntry($id, (new AliasResolver())($this, $definition));

                case $definition instanceof Closure:
                    return $this->addEntry($id, (new ClosureResolver)($this, $definition));

                case is_string($definition):
                    return $this->addEntry($id, (new ClassResolver)($this, $definition));
            }
        }

        if ($this->isAutowire()) {
            return $this->addEntry($id, (new ClassResolver)($this, $id));
        }

        throw NotFoundException::create($id);
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
     * @param array $definitions
     *
     * @throws ContainerException
     */
    public function addDefinitions(array $definitions): void
    {
        foreach ($definitions as $id => $definition) {
            if (array_key_exists($id, $this->definitions)) {
                throw new ContainerException("More than one definition is found for entry or class $id");
            }

            $this->definitions[$id] = $definition;
        }
    }

    /**
     * @param string $id
     * @param mixed $entry
     *
     * @return mixed
     */
    private function addEntry(string $id, $entry)
    {
        return $this->entries[$id] = $entry;
    }
}
