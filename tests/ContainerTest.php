<?php

/**
 * This file is part of the Container package.
 *
 * Copyright (c) Miloš Đurić <djuric.milos@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Laganica\Di\Test;

use Closure;
use InvalidArgumentException;
use Laganica\Di\ContainerBuilder;
use Laganica\Di\Definition\DefinitionInterface;
use Laganica\Di\Exception\CircularDependencyFoundException;
use Laganica\Di\Exception\ContainerException;
use Laganica\Di\Exception\InvalidDefinitionException;
use Laganica\Di\Exception\ClassNotFoundException;
use Laganica\Di\Exception\InvalidFactoryException;
use Laganica\Di\FactoryInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionProperty;
use function Laganica\Di\alias;
use function Laganica\Di\bind;
use function Laganica\Di\closure;
use function Laganica\Di\factory;
use function Laganica\Di\value;

interface ServiceInterface
{
}

class Dependency
{
}

class NotDependency
{
}

class Service implements ServiceInterface
{
    public function __construct(Dependency $dependency)
    {
    }
}

class ServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container)
    {
        return new Service($container->get(Dependency::class));
    }
}

class NonResolvableDependency
{
    public function __construct(NonResolvable $nonResolvable)
    {

    }
}

class NonResolvable
{
    public function __construct(NonResolvableDependency $nonResolvableDependency)
    {

    }
}

class Id
{
}

class AnnotationService
{
    /**
     * @Inject
     * @var Dependency
     */
    private $dependency;

    /**
     * @Inject
     * @var ServiceInterface
     */
    private $service;

    /**
     * @var NotDependency
     */
    private $notDependency;
}

/**
 * Class ContainerTest
 *
 * @package Laganica\Di\Test
 */
class ContainerTest extends TestCase
{
    /**
     * @throws
     *
     * @return void
     */
    public function testInterfaceToClassBinding(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ServiceInterface::class => bind(Service::class)
        ]);

        $this->assertInstanceOf(Service::class, $builder->build()->get(ServiceInterface::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testInlineFactory(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ServiceInterface::class => static function (ContainerInterface $container) {
                return new Service($container->get(Dependency::class));
            }
        ]);

        $this->assertInstanceOf(Service::class, $builder->build()->get(ServiceInterface::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testClosure(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ServiceInterface::class => closure(static function (ContainerInterface $container) {
                return new Service($container->get(Dependency::class));
            })
        ]);

        $this->assertInstanceOf(Service::class, $builder->build()->get(ServiceInterface::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testFactory(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ServiceInterface::class => factory(ServiceFactory::class)
        ]);

        $this->assertInstanceOf(Service::class, $builder->build()->get(ServiceInterface::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testAlias(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ServiceInterface::class => factory(ServiceFactory::class),
            'alias' => alias(ServiceInterface::class)
        ]);

        $this->assertInstanceOf(Service::class, $builder->build()->get('alias'));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testClass(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ServiceInterface::class => Service::class
        ]);

        $this->assertInstanceOf(Service::class, $builder->build()->get(ServiceInterface::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testValue(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            'count' => value(100)
        ]);

        $this->assertEquals(100, $builder->build()->get('count'));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testAnnotations(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ServiceInterface::class => factory(ServiceFactory::class)
        ]);
        $builder->useAnnotations(true);

        $entry = $builder->build()->get(AnnotationService::class);
        $this->assertInstanceOf(AnnotationService::class, $entry);

        $dependencyProperty = new ReflectionProperty(AnnotationService::class, 'dependency');
        $dependencyProperty->setAccessible(true);
        $dependencyPropertyValue = $dependencyProperty->getValue($entry);
        $this->assertInstanceOf(Dependency::class, $dependencyPropertyValue);

        $serviceProperty = new ReflectionProperty(AnnotationService::class, 'service');
        $serviceProperty->setAccessible(true);
        $servicePropertyValue = $serviceProperty->getValue($entry);
        $this->assertInstanceOf(ServiceInterface::class, $servicePropertyValue);

        $notDependencyProperty = new ReflectionProperty(AnnotationService::class, 'notDependency');
        $notDependencyProperty->setAccessible(true);
        $notDependencyPropertyValue = $notDependencyProperty->getValue($entry);
        $this->assertNull($notDependencyPropertyValue);
    }

    /**
     * @return void
     */
    public function testHas(): void
    {
        $builder = new ContainerBuilder();

        $this->assertTrue($builder->build()->has(Service::class));
    }

    /**
     * @throws ContainerException
     *
     * @return void
     */
    public function testHasInvalidDefinition(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ServiceInterface::class => 'InvalidClass'
        ]);

        $this->assertTrue($builder->build()->has(ServiceInterface::class));
    }

    /**
     * @return void
     */
    public function testDoesNotHave(): void
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(false);

        $this->assertFalse($builder->build()->has(ServiceInterface::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testNonResolvable(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            'alias' => alias(NonResolvable::class)
        ]);

        $class = NonResolvable::class;
        $this->expectException(CircularDependencyFoundException::class);
        $this->expectExceptionMessage("Circular dependency found for entry or class $class");

        $builder->build()->get(NonResolvable::class);
    }

    /**
     * @return void
     */
    public function testGetArgumentIsInteger(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument $id must be string, integer given');

        (new ContainerBuilder)->build()->get(100);
    }

    /**
     * @return void
     */
    public function testGetArgumentIsObject(): void
    {
        $class = Id::class;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument \$id must be string, $class given");

        (new ContainerBuilder)->build()->get(new Id);
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testEntryIsShared(): void
    {
        $container = (new ContainerBuilder)->build();

        $this->assertSame($container->get(Dependency::class), $container->get(Dependency::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testEntryIsNotShared(): void
    {
        $container = (new ContainerBuilder)->build();

        $dependency1 = $container->make(Dependency::class);
        $dependency2 = $container->make(Dependency::class);

        $this->assertEquals($dependency1, $dependency2);
        $this->assertNotSame($dependency1, $dependency2);
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testInvalidDefinition(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            'invalid-definition' => 100
        ]);

        $definitionClass = DefinitionInterface::class;
        $closureClass = Closure::class;
        $this->expectException(InvalidDefinitionException::class);
        $this->expectExceptionMessage("Argument \$definition must be either $definitionClass, $closureClass or string, integer given");

        $builder->build()->get('invalid-definition');
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testInvalidClass(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ServiceInterface::class => 'InvalidClass'
        ]);

        $this->expectException(ClassNotFoundException::class);
        $this->expectExceptionMessage('InvalidClass class not found');

        $builder->build()->get(ServiceInterface::class);
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testAutowiringInvalidClass(): void
    {
        $builder = new ContainerBuilder();

        $this->expectException(ClassNotFoundException::class);
        $this->expectExceptionMessage('InvalidClass class not found');

        $builder->build()->get('InvalidClass');
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testInvalidFactory(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ServiceInterface::class => factory(Service::class)
        ]);

        $class = Service::class;
        $interface = FactoryInterface::class;
        $this->expectException(InvalidFactoryException::class);
        $this->expectExceptionMessage("$class must implement $interface");

        $builder->build()->get(ServiceInterface::class);
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testInvalidFactoryClass(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ServiceInterface::class => factory('InvalidFactoryClass')
        ]);

        $this->expectException(ClassNotFoundException::class);
        $this->expectExceptionMessage('InvalidFactoryClass class not found');

        $builder->build()->get(ServiceInterface::class);
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testAddDefinitionDuplicate(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions([
            ServiceInterface::class => Service::class
        ]);

        $id = ServiceInterface::class;
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage("More than one definition is found for entry or class $id");

        $builder->addDefinitions([
            ServiceInterface::class => Service::class
        ]);
    }
}
