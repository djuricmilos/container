<?php

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
    private $autowire = true;

    /**
     * @var bool
     */
    private $annotations = false;

    public function __construct()
    {
        $this->init();
    }

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
    private function areAnnotationsEnabled(): bool
    {
        return $this->annotations;
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
        $container->setAutowire($this->isAutowireEnabled());
        $container->setAnnotations($this->areAnnotationsEnabled());
        $container->setDefinitions($this->getDefinitions());
        $this->init();

        return $container;
    }

    /**
     * @return void
     */
    private function init(): void
    {
        $this->setAutowire(true);
        $this->definitions = new ArrayObject();
    }
}
