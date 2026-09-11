<?php

declare(strict_types=1);

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

// On charge TON tableau de routes : [ ['GET', '/salles', [...]], ... ]
$routes = require __DIR__ . '/web.php';

// simpleDispatcher attend une fonction qui "remplit" un RouteCollector.
// Ici, on boucle sur ton tableau et on ajoute chaque ligne une par une.
$dispatcher = simpleDispatcher(function (RouteCollector $r) use ($routes): void {
    foreach ($routes as [$methode, $chemin, $handler]) {
        $r->addRoute($methode, $chemin, $handler);
    }
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        require dirname(__DIR__) . '/templates/error/404.php';
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        header('Allow: ' . implode(', ', $routeInfo[1]));
        require dirname(__DIR__) . '/templates/error/405.php';
        break;

    case Dispatcher::FOUND:
        [$classeControleur, $methode] = $routeInfo[1];
        $parametres = $routeInfo[2];

        $controleur = new $classeControleur();
        echo $controleur->$methode(...array_values($parametres));
        break;
}