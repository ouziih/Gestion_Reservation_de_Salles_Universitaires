<?php

declare(strict_types=1);

use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use DI\ContainerBuilder;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function DI\autowire;
use function FastRoute\simpleDispatcher;

$dispatch = static function (string $method, string $uri, ?array $data = null): string {
    $containerBuilder = new ContainerBuilder();
    $containerBuilder->addDefinitions([
        SalleRepositoryInterface::class => autowire(EloquentSalleRepository::class),
        ReservationRepositoryInterface::class => autowire(EloquentReservationRepository::class),
    ]);
    $container = $containerBuilder->build();

    $routeDefinitions = require __DIR__ . '/Routes.php';
    $dispatcher = simpleDispatcher(
        static function (RouteCollector $routes) use ($routeDefinitions): void {
            foreach ($routeDefinitions as [$routeMethod, $path, $handler]) {
                $routes->addRoute($routeMethod, $path, $handler);
            }
        }
    );

    $route = $dispatcher->dispatch($method, $uri);

    if ($route[0] === Dispatcher::NOT_FOUND) {
        http_response_code(404);
        require dirname(__DIR__) . '/templates/error/404.php';

        return '';
    }

    if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
        http_response_code(405);
        header('Allow: ' . implode(', ', $route[1]));
        require dirname(__DIR__) . '/templates/error/405.php';

        return '';
    }

    [, $handler, $parameters] = $route;
    $controller = $container->get($handler[0]);
    $arguments = array_map(
        static fn (string $value): int|string => ctype_digit($value) ? (int) $value : $value,
        array_values($parameters),
    );

    if ($data !== null) {
        $arguments[] = $data;
    }

    return $controller->{$handler[1]}(...$arguments);
};

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($uri === false || $uri === '') {
    $uri = '/';
}

echo $dispatch($method, $uri, $method === 'POST' ? $_POST : null);
