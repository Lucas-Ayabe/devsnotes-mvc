<?php
require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

/**
 * @var callable
 */
$routes = require_once __DIR__ . "/../src/Http/routes.php";
$dispatcher = FastRoute\simpleDispatcher($routes);

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = isset($_GET['path']) ? "/{$_GET['path']}" : '/';

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "NOT FOUND";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $controller = array_key_first($routeInfo[1]);
        $action = end($routeInfo[1]);

        (new $controller())->$action($routeInfo[2]);
        break;
    default:
        break;
}
