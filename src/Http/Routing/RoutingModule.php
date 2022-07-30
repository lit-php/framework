<?php

declare(strict_types=1);

namespace Strike\Framework\Http\Routing;

use Strike\Framework\Core\ApplicationInterface;
use Strike\Framework\Core\Container\ContainerInterface;
use Strike\Framework\Core\Filesystem\Filesystem;
use Strike\Framework\Core\ModuleInterface;

class RoutingModule implements ModuleInterface
{
    public function __construct(
        private readonly ApplicationInterface $app,
    ) {
    }

    public function register(): void
    {
        $this->app->bind(RouteRegistrar::class, fn () => new RouteRegistrar(), true);
        $this->app->bind(
            Router::class,
            fn (ContainerInterface $container) => new Router(
                $container->get(RouteRegistrar::class),
                $container->get(Filesystem::class),
                $this->app->getRoutesPath(),
                $this->app->getCachedRoutesPath(),
            ),
            true,
        );
    }

    public function load(): void
    {
        /** @var Router $router */
        $router = $this->app->get(Router::class);
        $router->loadRoutes();
    }
}
