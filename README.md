# Container

[![Build Status][ico-build]][link-build]
[![Code Quality][ico-code-quality]][link-code-quality]
[![Code Coverage][ico-code-coverage]][link-code-coverage]
[![Latest Version][ico-version]][link-packagist]
[![PDS Skeleton][ico-pds]][link-pds]

[ico-version]: https://img.shields.io/packagist/v/djuricmilos/container.svg
[ico-build]: https://api.travis-ci.com/djuricmilos/container.svg?branch=master
[ico-code-coverage]: https://img.shields.io/scrutinizer/coverage/g/djuricmilos/container.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/djuricmilos/container.svg
[ico-pds]: https://img.shields.io/badge/pds-skeleton-blue.svg

[link-packagist]: https://packagist.org/packages/djuricmilos/container
[link-build]: https://travis-ci.com/djuricmilos/container
[link-code-coverage]: https://scrutinizer-ci.com/g/djuricmilos/container/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/djuricmilos/container
[link-pds]: https://github.com/php-pds/skeleton
[link-author]: https://github.com/djuricmilos
[link-contributors]: ../../contributors

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following command to install the latest version of a package and add it to your project's `composer.json`:

```bash
composer require djuricmilos/container
```

## Usage

### Simple container

The fastest way to create container is to instantiate `Laganica\Di\Container` class.

```php
$container = new Laganica\Di\Container();
```

By default, autowiring is enabled and annotations are disabled.

### Configuring the container

```php
$builder = new Laganica\Di\ContainerBuilder();
$builder->setAutowire(false);
$builder->setAnnotations(false);

$container = $builder->build();
```

### Definitions

If object of service class cannot be created by using autowiring we have to create a definition for that service.
Definition is telling the container how to instantiate a service class.

#### Interface to Class binding

Container will use class name passed to bind method to create instance of that class.

```php
$builder = new Laganica\Di\ContainerBuilder();
$builder->addDefinitions([
    ServiceInterface::class => Laganica\Di\bind(Service::class)
]);

$container = $builder->build();
$service = $container->get(ServiceInterface::class);
```

#### Class name

The same as bind, just shorter.

```php
$builder = new Laganica\Di\ContainerBuilder();
$builder->addDefinitions([
    ServiceInterface::class => Service::class
]);

$container = $builder->build();
$service = $container->get(ServiceInterface::class);
```

#### Closure

Container will invoke closure to create service instance.
Note that `$container` is available as closure parameter.

```php
$builder = new Laganica\Di\ContainerBuilder();
$builder->addDefinitions([
    ServiceInterface::class => Laganica\Di\closure(static function (Psr\Container\ContainerInterface $container) {
        return new Service($container->get(Dependency::class));
    })
]);

$container = $builder->build();
$service = $container->get(ServiceInterface::class);
```

#### Inline factory

The same as closure, just shorter.

```php
$builder = new Laganica\Di\ContainerBuilder();
$builder->addDefinitions([
    ServiceInterface::class => static function (Psr\Container\ContainerInterface $container) {
        return new Service($container->get(Dependency::class));
    }
]);

$container = $builder->build();
$service = $container->get(ServiceInterface::class);
```

#### Factory

Container will invoke object of factory class to create service instance.

```php
$builder = new Laganica\Di\ContainerBuilder();
$builder->addDefinitions([
    ServiceInterface::class => Laganica\Di\factory(ServiceFactory::class)
]);

$container = $builder->build();
$service = $container->get(ServiceInterface::class);
```

ServiceFactory is class that implements `Laganica\Di\FactoryInterface` interface and whose __invoke method is used to define how service is created.

```php
class ServiceFactory implements Laganica\Di\FactoryInterface
{
    public function __invoke(Psr\Container\ContainerInterface $container)
    {
        return new Service($container->get(Dependency::class));
    }
}
```

#### Alias

Container will use entry name passed to alias method to find other entry and use it to create service instance.

```php
$builder = new Laganica\Di\ContainerBuilder();
$builder->addDefinitions([
    ServiceInterface::class => Service::class,
    'service-alias' => Laganica\Di\alias(ServiceInterface::class)
]);

$container = $builder->build();
$service = $container->get('service-alias');
```

#### Values

Container will return value passed to value method.

```php
$builder = new Laganica\Di\ContainerBuilder();
$builder->addDefinitions([
    'count' => value(100)
]);

$container = $builder->build();
$count = $container->get('count');
```

### Annotations

Container will use `@Inject` annotation on `$dependency` property in `Service` class to inject `Dependency`.
As autowiring is enabled by default, it will be used to create instance of `Dependency` class.

```php
class Service
{
    /**
     * @Inject
     * @var Dependency
     */
    private $dependency;
}

$builder = new ContainerBuilder();
$builder->setAnnotations(true);

$container = $builder->build();
$service = $container->get(Service::class);
```

## Credits

- [All Contributors][link-contributors]

## License

Released under MIT License - see the [License File](LICENSE) for details.