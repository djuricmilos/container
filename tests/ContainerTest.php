<?php

namespace Laganica\Di\Test;

use Closure;
use InvalidArgumentException;
use Laganica\Di\ContainerBuilder;
use Laganica\Di\Definition\DefinitionInterface;
use Laganica\Di\Exception\CircularDependencyFoundException;
use Laganica\Di\Exception\ContainerException;
use Laganica\Di\Exception\InvalidDefinitionException;
use Laganica\Di\Exception\ClassNotFoundException;
use Laganica\Di\FactoryInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
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
        $definitions = [
            ServiceInterface::class => bind(Service::class)
        ];

        $container = (new ContainerBuilder)->build();
        $container->addDefinitions($definitions);

        $this->assertInstanceOf(Service::class, $container->get(ServiceInterface::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testInlineFactory(): void
    {
        $definitions = [
            ServiceInterface::class => static function (ContainerInterface $container) {
                return new Service($container->get(Dependency::class));
            }
        ];

        $container = (new ContainerBuilder)->build();
        $container->addDefinitions($definitions);

        $this->assertInstanceOf(Service::class, $container->get(ServiceInterface::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testClosure(): void
    {
        $definitions = [
            ServiceInterface::class => closure(static function (ContainerInterface $container) {
                return new Service($container->get(Dependency::class));
            })
        ];

        $container = (new ContainerBuilder)->build();
        $container->addDefinitions($definitions);

        $this->assertInstanceOf(Service::class, $container->get(ServiceInterface::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testFactory(): void
    {
        $definitions = [
            ServiceInterface::class => factory(ServiceFactory::class)
        ];

        $container = (new ContainerBuilder)->build();
        $container->addDefinitions($definitions);

        $this->assertInstanceOf(Service::class, $container->get(ServiceInterface::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testAlias(): void
    {
        $definitions = [
            ServiceInterface::class => factory(ServiceFactory::class),
            'alias' => alias(ServiceInterface::class)
        ];

        $container = (new ContainerBuilder)->build();
        $container->addDefinitions($definitions);

        $this->assertInstanceOf(Service::class, $container->get('alias'));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testClass(): void
    {
        $definitions = [
            ServiceInterface::class => Service::class
        ];

        $container = (new ContainerBuilder)->build();
        $container->addDefinitions($definitions);

        $this->assertInstanceOf(Service::class, $container->get(ServiceInterface::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testValue(): void
    {
        $definitions = [
            'count' => value(100)
        ];

        $container = (new ContainerBuilder)->build();
        $container->addDefinitions($definitions);

        $this->assertEquals(100, $container->get('count'));
    }

    /**
     * @return void
     */
    public function testHas(): void
    {
        $container = (new ContainerBuilder)->build();

        $this->assertTrue($container->has(Service::class));
    }

    /**
     * @throws ContainerException
     *
     * @return void
     */
    public function testHasInvalidDefinition(): void
    {
        $definitions = [
            ServiceInterface::class => 'InvalidClass'
        ];

        $container = (new ContainerBuilder)->build();
        $container->addDefinitions($definitions);

        $this->assertTrue($container->has(ServiceInterface::class));
    }

    /**
     * @return void
     */
    public function testDoesNotHave(): void
    {
        $containerBuilder = new ContainerBuilder;
        $containerBuilder->setAutowire(false);

        $container = ($containerBuilder)->build();

        $this->assertFalse($container->has(ServiceInterface::class));
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testNonResolvable(): void
    {
        $definitions = [
            'alias' => alias(NonResolvable::class)
        ];

        $container = (new ContainerBuilder)->build();
        $container->addDefinitions($definitions);

        $class = NonResolvable::class;
        $this->expectException(CircularDependencyFoundException::class);
        $this->expectExceptionMessage("Circular dependency found for entry or class $class");

        $container->get(NonResolvable::class);
    }

    /**
     * @return void
     */
    public function testGetArgumentIsInteger(): void
    {
        $container = (new ContainerBuilder)->build();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument $id must be string, integer given');

        $container->get(100);
    }

    /**
     * @return void
     */
    public function testGetArgumentIsObject(): void
    {
        $container = (new ContainerBuilder)->build();

        $class = Id::class;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument \$id must be string, $class given");

        $container->get(new Id);
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
    public function testInvalidDefinition(): void
    {
        $definitions = [
            'invalid-definition' => 100
        ];

        $container = (new ContainerBuilder)->build();
        $container->addDefinitions($definitions);

        $definitionClass = DefinitionInterface::class;
        $closureClass = Closure::class;
        $this->expectException(InvalidDefinitionException::class);
        $this->expectExceptionMessage("Argument \$definition must be either $definitionClass, $closureClass or string, integer given");

        $container->get('invalid-definition');
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testInvalidClass(): void
    {
        $definitions = [
            ServiceInterface::class => 'InvalidClass'
        ];

        $container = (new ContainerBuilder)->build();
        $container->addDefinitions($definitions);

        $this->expectException(ClassNotFoundException::class);
        $this->expectExceptionMessage('InvalidClass class not found');

        $container->get(ServiceInterface::class);
    }

    /**
     * @throws
     *
     * @return void
     */
    public function testAddDefinitionDuplicate(): void
    {
        $container = (new ContainerBuilder)->build();
        $container->addDefinition(ServiceInterface::class, Service::class);

        $id = ServiceInterface::class;
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage("More than one definition is found for entry or class $id");

        $container->addDefinition(ServiceInterface::class, Service::class);
    }
}
