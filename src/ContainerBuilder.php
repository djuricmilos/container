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
use Laganica\Di\Definition\DefinitionFactory;
use Laganica\Di\Exception\ContainerException;
use Laganica\Di\Resolver\ResolverFactory;

/**
 * Class ContainerBuilder
 *
 * @package Laganica\Di
 */
class ContainerBuilder
{
    /**
     * @var ArrayObject
     */
    private $definitions;

    /**
     * @var bool
     */
    private $useAutowiring;

    /**
     * @var bool
     */
    private $useAnnotations;

    public function __construct()
    {
        $this->init();
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
    private function hasAnnotationsEnabled(): bool
    {
        return $this->useAnnotations;
    }

    /**
     * @param array $definitions
     *
     * @throws ContainerException
     */
    public function addDefinitions(array $definitions): void
    {
        foreach ($definitions as $id => $definition) {
            if ($this->definitions->offsetExists($id)) {
                throw new ContainerException("More than one definition is found for entry or class $id");
            }

            $this->definitions->offsetSet($id, $definition);
        }
    }

    /**
     * @return ArrayObject
     */
    private function getDefinitions(): ArrayObject
    {
        return $this->definitions;
    }

    /**
     * @return Container
     */
    public function build(): Container
    {
        $container = new Container(new DefinitionFactory(), new ResolverFactory());
        $container->useAutowiring($this->hasAutowiringEnabled());
        $container->useAnnotations($this->hasAnnotationsEnabled());
        $container->setDefinitions($this->getDefinitions());

        $this->reset();

        return $container;
    }

    /**
     * @return void
     */
    private function init(): void
    {
        $this->useAutowiring(true);
        $this->useAnnotations(false);
        $this->definitions = new ArrayObject();
    }

    /**
     * @return void
     */
    private function reset(): void
    {
        $this->init();
    }
}
