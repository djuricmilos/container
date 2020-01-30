<?php

namespace Laganica\Di\Test;

use Laganica\Di\Container;
use Laganica\Di\FactoryInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use function Laganica\Di\alias;
use function Laganica\Di\bind;
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
    /**
     * @param Dependency $dependency
     */
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

        $container = new Container();
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

        $container = new Container();
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

        $container = new Container();
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

        $container = new Container();
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

        $container = new Container();
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

        $container = new Container();
        $container->addDefinitions($definitions);

        $this->assertEquals(100, $container->get('count'));
    }
}
